<?php

namespace BMI\Plugin\Zipper;

use BMI\Plugin\Backup_Migration_Plugin as BMP;
use BMI\Plugin\BMI_Logger as Logger;
use BMI\Plugin\Dashboard as Dashboard;
use BMI\Plugin\Database\BMI_Database as Database;
use BMI\Plugin\Database\BMI_Database_Exporter as BetterDatabaseExport;
use BMI\Plugin\Progress\BMI_ZipProgress as Progress;
use BMI\Plugin\Heart\BMI_Backup_Heart as Bypasser;

class Zip {
  protected $lib;
  protected $org_files;
  protected $new_file_path;
  protected $new_file_name;
  protected $backupname;
  protected $zip_progress;

  protected $extr_file;
  protected $extr_dirc;
  protected $start_zip;

  public function __construct() {
    $this->lib = 0;
    $this->extr_file = 0;
    $this->new_file_path = 0;
    $this->org_files = [];
  }

  public function zip_start($file_path, $files = [], $name = '', &$zip_progress = null, $start = null) {

    // save the new file path
    $this->new_file_path = $file_path;
    $this->backupname = $name;
    $this->zip_progress = $zip_progress;
    $this->start_zip = $start;

    if (sizeof($files) > 0) {
      $this->org_files = $files;
    }

    // Some php installations doesn't have the ZipArchive
    // So in this case we'll use another lib called PclZip
    if (class_exists("ZipArchive") || class_exists("\ZipArchive")) {
      $this->lib = 1;
    } else {
      $this->lib = 2;
    }

    return true;

  }

  public function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val) - 1]);
    $val = substr($val, 0, -1);

    switch ($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= 1024;
            // no break
        case 'm':
            $val *= 1024;
            // no break
        case 'k':
            $val *= 1024;
    }

    return $val;
  }

  public function zip_failed($error) {
    Logger::error(__("There was an error during backup (packing)...", 'backup-backup'));
    Logger::error(print_r($error, true));

    if ($this->zip_progress != null) {
      $this->zip_progress->log(__("Issues during backup (packing)...", 'backup-backup'), 'ERROR');
      $this->zip_progress->log(print_r($error, true), 'ERROR');
      $this->zip_progress->log('#004', 'END-CODE');
    }
  }

  public function restore_failed($error) {
    Logger::error(__("There was an error during restore process (extracting)...", 'backup-backup'));
    Logger::error($error);

    if ($this->zip_progress != null) {
      $this->zip_progress->log(__("Issues during restore process (extracting)...", 'backup-backup'), 'ERROR');
      $this->zip_progress->log($error, 'ERROR');
      $this->zip_progress->log('#004', 'END-CODE');
    }
  }

  public function zip_add($in) {

    // Just to make sure.. if the user haven't called the earlier method
    if ($this->lib === 0 || $this->new_file_path === 0) {
      throw new \Exception("PHP-ZIP: must call zip_start before zip_add");
    }

    // Push file
    array_push($this->org_files, $in);

    // Return
    return true;
  }

  public function createDatabaseDump($dbbackupname, $better_database_files_dir, &$database_file, $database_file_dir, $dbBackupEngine = 'v4') {

    $shouldBackupDB = apply_filters('bmip_database_backup', Dashboard\bmi_get_config('BACKUP:DATABASE') == 'true');
    if ( $shouldBackupDB ) {

      if (Dashboard\bmi_get_config('OTHER:BACKUP:DB:SINGLE:FILE') == 'true') {

        // Require Database Manager
        require_once BMI_INCLUDES . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'manager.php';

        // Logs
        $this->zip_progress->log(__("Making single-file database backup (using deprecated engine, due to used settings)", 'backup-backup'), 'STEP');

        // Get database dump
        $databaser = new Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $databaser->exportDatabase($dbbackupname);

        // Fix for newer version
        $this->db_exporter_queries = 0;
        $this->zip_progress->total_queries = 0;
        $this->db_exporter_files = [];

        $this->dbDumped = true;
        $this->zip_progress->log(__("Database size: ", 'backup-backup') . BMP::humanSize(filesize($database_file)), 'INFO');

      } else {

        if ($dbBackupEngine == 'v4') {

          // Require Database Manager
          require_once BMI_INCLUDES . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'better-backup-v3.php';

          // Get database dump
          $this->zip_progress->log(__("Making database backup (using v3 engine, requires at least v1.2.2 to restore)", 'backup-backup'), 'STEP');

        } else {

          // Require Database Manager
          require_once BMI_INCLUDES . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'better-backup.php';

          // Get database dump
          $this->zip_progress->log(__("Making database backup (using v2 engine, requires at least v1.1.0 to restore)", 'backup-backup'), 'STEP');

        }

        $this->zip_progress->log(__("Iterating database...", 'backup-backup'), 'INFO');

        if (!is_dir($better_database_files_dir)) @mkdir($better_database_files_dir, 0755, true);
        $db_exporter = new BetterDatabaseExport($better_database_files_dir, $this->zip_progress);
        
        $this->zip_progress->log('Exporting database via zip.php', 'VERBOSE');
        $db_exporter->export();
        $this->db_exporter_files = $db_exporter->files;
        $this->db_exporter_queries = $db_exporter->total_queries;

        $this->zip_progress->total_queries = $this->db_exporter_queries;

        $this->dbDumped = true;
        $this->zip_progress->log(__("Database backup finished", 'backup-backup'), 'SUCCESS');

      }

    } else {

      $this->dbDumped = false;
      $this->zip_progress->log(__("Omitting database backup (due to settings)...", 'backup-backup'), 'WARN');
      $database_file = false;
      $this->db_exporter_files = [];
      $this->db_exporter_queries = 0;
      $this->zip_progress->total_queries = 0;

    }

  }
  
  public function saveRemoteSettings($settings) {
    $settings_name = 'currentBackupConfig.' . 'php';
    $settings_path = BMP::fixSlashes(BMI_TMP . DIRECTORY_SEPARATOR . $settings_name);
    
    if (file_exists($settings_path)) @unlink($settings_path);
    file_put_contents($settings_path, '<?php //' . json_encode($settings));
  }
  
  // Cut Path for ZIP structure
  public function cutDir($file) {

    if (substr($file, -4) === '.sql') {

      $path = 'db_tables' . DIRECTORY_SEPARATOR . basename($file);

    } else {

      $path = basename($file);

    }
    
    $path = str_replace('\\', '/', $path);
    return $path;
    
  }

  public function zip_end($force_lib = false, $cron = false) {

    // v4 for new one, v3 for old one
    $dbBackupEngine = 'v4';

    // Try to set limit
    $this->zip_progress->log(__("Smart memory calculation...", 'backup-backup'), 'STEP');
    if ((intval($this->return_bytes(ini_get('memory_limit'))) / 1024 / 1024) < 384) @ini_set('memory_limit', '384M');
    if (defined('WP_MAX_MEMORY_LIMIT')) $maxwp = WP_MAX_MEMORY_LIMIT;
    else $maxwp = '1M';

    $memory_limit = (intval($this->return_bytes(ini_get('memory_limit'))) / 1024 / 1024);
    $maxwp = (intval($this->return_bytes($maxwp)) / 1024 / 1024);

    if ($maxwp < $memory_limit) $memory_limit = $maxwp;
    $this->zip_progress->log(str_replace('%s', $memory_limit, __("There is %s MBs of memory to use", 'backup-backup')), 'INFO');
    $this->zip_progress->log(str_replace('%s', $maxwp, __("WordPress memory limit: %s MBs", 'backup-backup')), 'INFO');
    $safe_limit = intval($memory_limit / 4);
    if ($safe_limit >= 1024) $safe_limit = 256;
    else if ($safe_limit >= 512) $safe_limit = 128;
    else if ($safe_limit === 384) $safe_limit = 96;
    else if ($safe_limit > 64) $safe_limit = 64;

    // $real_memory = intval(memory_get_usage() * 0.9 / 1024 / 1024);
    // if ($real_memory < $safe_limit) $safe_limit = $real_memory;
    $safe_limit = intval($safe_limit * 0.9);

    $this->zip_progress->log(str_replace('%s', $safe_limit, __("Setting the safe limit to %s MB", 'backup-backup')), 'SUCCESS');

    $abs = BMP::fixSlashes(ABSPATH) . DIRECTORY_SEPARATOR;

    $dbbackupname = 'bmi_database_backup.sql';
    $database_file = BMP::fixSlashes(BMI_TMP . DIRECTORY_SEPARATOR . $dbbackupname);
    $database_file_dir = BMP::fixSlashes((dirname($database_file))) . DIRECTORY_SEPARATOR;
    $better_database_files_dir = $database_file_dir . 'db_tables';

    // force usage of specific lib (for testing purposes)
    if ($force_lib === 2) {

      $this->lib = 2;

    } elseif ($force_lib === 1) {

      $this->lib = 1;

    }

    $this->dbDumped = false;
    $this->db_exporter_queries = 0;
    $this->zip_progress->total_queries = 0;
    $this->db_exporter_files = [];

    // just to make sure.. if the user haven't called the earlier method
    if ($this->lib === 0 || $this->new_file_path === 0) {
      throw new \Exception('PHP-ZIP: zip_start and zip_add haven\'t been called yet');
    }

    // using zipArchive class
    // if ($this->lib === 1) {
    //
    //   // Create DB Dump
    //   $this->createDatabaseDump($dbbackupname, $better_database_files_dir, $database_file, $database_file_dir);
    //
    //   // All files
    //   $max = sizeof($this->org_files);
    //   $this->zip_progress->log(__("Making archive", 'backup-backup'), 'STEP');
    //   $this->zip_progress->log(__("Compressing...", 'backup-backup'), 'INFO');
    //
    //   // Verbose
    //   $this->zip_progress->log(__("Using Zlib to create Backup", 'backup-backup'));
    //
    //   $lib = new \ZipArchive();
    //   if (!$lib->open($this->new_file_path, \ZipArchive::CREATE)) {
    //     throw new \Exception('PHP-ZIP: Permission Denied or zlib can\'t be found');
    //   }
    //
    //   // Add each file
    //   for ($i = 0; $i < $max; $i++) {
    //     $file = $this->org_files[$i];
    //     $zippath = substr($file, strlen($abs));
    //     $lib->addFile($file, 'wordpress' . DIRECTORY_SEPARATOR . $zippath);
    //
    //     if ($i % 100 === 0) {
    //       if (file_exists(BMI_BACKUPS . DIRECTORY_SEPARATOR . '.abort')) {
    //         break;
    //       }
    //       $this->zip_progress->progress($i + 1 . '/' . $max);
    //     }
    //
    //     if (($i + 1) % 500 === 0 || $i == 0) {
    //       if (($i + 1) < $max) {
    //         $this->zip_progress->log((__("Milestone: ", 'backup-backup') . ($i + 1) . '/' . $max), 'info');
    //       }
    //     }
    //   }
    //
    //   if (file_exists(BMI_BACKUPS . DIRECTORY_SEPARATOR . '.abort')) {
    //
    //     // close the archive
    //     $lib->close();
    //   } else {
    //     $this->zip_progress->log((__("Milestone: ", 'backup-backup') . $max . '/' . $max), 'info');
    //     $this->zip_progress->log(__("Compressed ", 'backup-backup') . $max . __(" files", 'backup-backup'), 'SUCCESS');
    //
    //     // Log time of ZIP Process
    //     $this->zip_progress->log(__("Archiving of ", 'backup-backup') . $max . __(" files took: ", 'backup-backup') . number_format(microtime(true) - $this->start_zip, 2) . 's');
    //
    //     $this->zip_progress->log(__("Finalizing backup", 'backup-backup'), 'STEP');
    //     $this->zip_progress->log(__("Adding manifest...", 'backup-backup'), 'INFO');
    //     $this->zip_progress->log(__("Closing files and archives", 'backup-backup'), 'STEP');
    //     $this->zip_progress->log('#001', 'END-CODE');
    //
    //     $this->zip_progress->end();
    //     $logs = file_get_contents(BMI_BACKUPS . DIRECTORY_SEPARATOR . 'latest.log');
    //     $this->zip_progress->start(true);
    //
    //     if ($database_file !== false) {
    //       if (Dashboard\bmi_get_config('OTHER:BACKUP:DB:SINGLE:FILE') == 'true') {
    //         if (file_exists($database_file)) {
    //           $lib->addFile($database_file, 'bmi_database_backup.sql');
    //         }
    //       } else {
    //         if ($db_exporter_files && sizeof($db_exporter_files) > 0) {
    //           for ($i = 0; $i < sizeof($db_exporter_files); ++$i) {
    //             $lib->addFile($db_exporter_files[$i], 'db_tables' . DIRECTORY_SEPARATOR . basename($db_exporter_files[$i]));
    //           }
    //         }
    //       }
    //     }
    //
    //     $lib->addFromString('bmi_backup_manifest.json', $this->zip_progress->createManifest($dbBackupEngine));
    //     $lib->addFromString('bmi_logs_this_backup.log', $logs);
    //     $this->zip_progress->progress($max . '/' . $max);
    //
    //     // close the archive
    //     $lib->close();
    //   }
    // }

    // using PclZip
    if ($this->lib === 2) {

      // All files
      $max = sizeof($this->org_files);

      $legacyVersion = apply_filters('bmi_legacy_version', BMI_LEGACY_VERSION);
      $legacyHardVersion = apply_filters('bmi_legacy_hard_version', BMI_LEGACY_HARD_VERSION);
      // Verbose
      $legacy = $legacyVersion;
      if ($legacy) $legacy = $legacyHardVersion;
      if (class_exists('\ZipArchive') || class_exists('ZipArchive')) {
        $this->zip_progress->log(__("ZipArchive is available this process should use ZipArchive", 'backup-backup'), 'INFO');
      } else {
        $this->zip_progress->log(__("Using PclZip module to create the backup", 'backup-backup'), 'INFO');
      }
      if (!$legacyVersion) {
        $this->zip_progress->log(__("Legacy setting: Using server-sided script and cURL based loop for better capabilities", 'backup-backup'), 'INFO');
      } elseif (!$legacyHardVersion) {
        $this->zip_progress->log(__("Legacy setting: Using user browser as middleware for full capabilities", 'backup-backup'), 'INFO');
      } else {

        $this->zip_progress->log(__("Legacy setting: Using default modules depending on user server", 'backup-backup'), 'INFO');

        // Create DB Dump
        $this->createDatabaseDump($dbbackupname, $better_database_files_dir, $database_file, $database_file_dir, $dbBackupEngine);

        $this->zip_progress->log(__("Making archive", 'backup-backup'), 'STEP');
        $this->zip_progress->log(__("Compressing...", 'backup-backup'), 'INFO');

      }

      // Run the backup in background
      $cliEnabled = false;
      if (defined('BMI_CLI_ENABLED')) $cliEnabled = apply_filters('bmi_cli_enabled', BMI_CLI_ENABLED);
      if ((!defined('BMI_USING_CLI_FUNCTIONALITY') || BMI_USING_CLI_FUNCTIONALITY === false) && ($legacy === false || $cliEnabled === true) && sizeof($this->org_files) > 10 && !defined('BMI_CLI_FAILED')) {
        file_put_contents($database_file_dir . 'bmi_backup_manifest.json', $this->zip_progress->createManifest($dbBackupEngine));
        // $url = plugins_url('') . '/backup-backup/includes/middleware-backup-proxy.php';
        $url = admin_url('admin-ajax.php');
        $identy = 'BMI-' . rand(10000000, 99999999);
        $remote_settings = [
          'identy' => $identy,
          'manifest' => $database_file_dir . 'bmi_backup_manifest.json',
          'backupname' => $this->backupname,
          'safelimit' => $safe_limit,
          'rev' => BMI_REV,
          'total_files' => sizeof($this->org_files),
          'filessofar' => 0,
          'start' => microtime(true),
          'config_dir' => BMI_CONFIG_DIR,
          'content_dir' => trailingslashit(WP_CONTENT_DIR),
          'backup_dir' => BMI_BACKUPS,
          'abs_dir' => trailingslashit(ABSPATH),
          'root_dir' => plugin_dir_path(BMI_ROOT_FILE),
          'browser' => false,
          'shareallowed' => BMP::canShareLogsOrShouldAsk(),
          'dbiteratio' => 0,
          'it' => 0,
          'dbit' => 0,
          'dblast' => 0,
          'bmitmp' => BMI_TMP,
          'url' => $url,
          'db_v2_engine' => false,
          'final_made' => false,
          'final_batch' => false,
          'dbitJustFinished' => false,
          'useragent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'WordPress.org Site Self Request'
        ];
        $fix = true;
        $Xfiles = glob(BMI_TMP . DIRECTORY_SEPARATOR . '.BMI-*');
        foreach ($Xfiles as $xfile) if (is_file($xfile)) unlink($xfile);
        touch(BMI_TMP . DIRECTORY_SEPARATOR . '.' . $identy);

        if ($fix === true) {
          if ($legacyHardVersion === false && $cron === false) {
            $remote_settings['browser'] = true;
            $this->zip_progress->log(__("Saving backup configuration for current session...", 'backup-backup'), 'INFO');
            $this->saveRemoteSettings($remote_settings);
            $this->zip_progress->log(__("Sending confirmation to user browser to keep pinging the process.", 'backup-backup'), 'INFO');
            BMP::res(['status' => 'background_hard', 'filename' => $this->backupname, 'url' => $url]);
            exit;
          } else {
            $this->zip_progress->log(__("Saving backup configuration for current session...", 'backup-backup'), 'INFO');
            $this->saveRemoteSettings($remote_settings);
            $this->zip_progress->log(__('Starting background process on server-side...', 'backup-backup'), 'INFO');
            require_once BMI_INCLUDES . '/backup-process.php';
            $request = new Bypasser($identy, BMI_CONFIG_DIR, trailingslashit(WP_CONTENT_DIR), BMI_BACKUPS, trailingslashit(ABSPATH), plugin_dir_path(BMI_ROOT_FILE));
            $request->send_beat(true, $this->zip_progress);
          }
        }

        sleep(2);
        if (file_exists(BMI_BACKUPS . DIRECTORY_SEPARATOR . '.running')) {
          if (file_exists(BMI_TMP . DIRECTORY_SEPARATOR . '.' . $identy . '-running')) {
            // $this->zip_progress->log(__('Request received correctly – backup is running.', 'backup-backup'), 'SUCCESS');
            if ($cron === true) return ['status' => 'background', 'filename' => $this->backupname];
            else BMP::res(['status' => 'background', 'filename' => $this->backupname]);
            exit;
          } else {
            $this->zip_progress->log(__('Could not find any response from the server, trying again in 3 seconds.', 'backup-backup'), 'WARN');
            sleep(3);
            if (file_exists(BMI_TMP . DIRECTORY_SEPARATOR . '.' . $identy . '-running')) {
              // $this->zip_progress->log(__('Request received correctly – backup is running.', 'backup-backup'), 'SUCCESS');
              if ($cron === true) return ['status' => 'background', 'filename' => $this->backupname];
              else BMP::res(['status' => 'background', 'filename' => $this->backupname]);
              exit;
            } else {
              $this->zip_progress->log(__('Still nothing backup probably is not running.', 'backup-backup'), 'WARN');
              if (file_exists(BMI_TMP . DIRECTORY_SEPARATOR . '.' . $identy . '-running')) @unlink(BMI_TMP . DIRECTORY_SEPARATOR . '.' . $identy . '-running');
              if (file_exists(BMI_TMP . DIRECTORY_SEPARATOR . '.' . $identy)) @unlink(BMI_TMP . DIRECTORY_SEPARATOR . '.' . $identy);
              throw new \Exception('Backup could not run on your server, please check global logs.');
            }
          }
        } else {
          if ($cron === true) return ['status' => 'background', 'filename' => $this->backupname];
          else BMP::res(['status' => 'background', 'filename' => $this->backupname]);
          exit;
        }

        // ob_end_clean();
        exit;
      } else {
        if (defined('BMI_USING_CLI_FUNCTIONALITY') && BMI_USING_CLI_FUNCTIONALITY === true) {
          $this->zip_progress->log(__("Backup is running under PHP CLI environment.", 'backup-backup'), 'INFO');
        } else {
          $this->zip_progress->log(__("Backup will run as single-request, may be unstable...", 'backup-backup'), 'WARN');
        }
      }
      
      if ($this->dbDumped === false) {
        $this->createDatabaseDump($dbbackupname, $better_database_files_dir, $database_file, $database_file_dir);
      }
      
      $zipArchive = false;
      if (class_exists('\ZipArchive') || class_exists('ZipArchive')) {
        if (!isset($zip)) {
          $zip = new \ZipArchive();
        }

        if ($zip) {
          $zipArchive = true;
        } else {
          $zipArchive = false;
        }
      }
      
      // Check if PclZip exists
      if ($zipArchive === false) {
        if (!class_exists('PclZip')) {
          if (!defined('PCLZIP_TEMPORARY_DIR')) {
            $bmi_tmp_dir = BMI_TMP;
            if (!file_exists($bmi_tmp_dir)) {
              @mkdir($bmi_tmp_dir, 0775, true);
            }

            define('PCLZIP_TEMPORARY_DIR', $bmi_tmp_dir . DIRECTORY_SEPARATOR . 'bmi-');
          }
          
          if (!defined('PCLZIP_READ_BLOCK_SIZE')) {
            define('PCLZIP_READ_BLOCK_SIZE', 32768);
          }
        }

        // Require the LIB and check if it's compatible
        if (defined('BMI_PRO_PCLZIP') && file_exists(BMI_PRO_PCLZIP)) {
          $this->zip_progress->log(__('Using dedicated PclZIP for Premium Users.', 'backup-backup'), 'INFO');
          require_once BMI_PRO_PCLZIP;
        } else {
          require_once trailingslashit(ABSPATH) . 'wp-admin/includes/class-pclzip.php';
        }
        
        // Get/Create the Archive
        if (!$lib = new \PclZip($this->new_file_path)) {
          throw new \Exception('PHP-ZIP: Permission Denied or zlib can\'t be found');
        }
        
      } else {
        
        if (file_exists($this->new_file_path)) $zip->open($this->new_file_path);
        else $zip->open($this->new_file_path, \ZipArchive::CREATE);
        
        $this->zip_progress->log('Using ZipArchive extension for this backup process.', 'INFO');
        
      }

      // require the lib
      // if (!class_exists('PclZip')) {
      //   if (!defined('PCLZIP_TEMPORARY_DIR')) {
      //     $bmi_tmp_dir = BMI_TMP;
      //     if (!file_exists($bmi_tmp_dir)) {
      //       @mkdir($bmi_tmp_dir, 0775, true);
      //     }
      //     define('PCLZIP_TEMPORARY_DIR', $bmi_tmp_dir . DIRECTORY_SEPARATOR . 'bmi-');
      //   }
      //   if (defined('BMI_PRO_PCLZIP') && file_exists(BMI_PRO_PCLZIP)) {
      //     $this->zip_progress->log(__('Using dedicated PclZIP for Premium Users.', 'backup-backup'), 'INFO');
      //     require_once BMI_PRO_PCLZIP;
      //   } else {
      //     require_once trailingslashit(ABSPATH) . 'wp-admin/includes/class-pclzip.php';
      //   }
      // }
      $common = $this->org_files;

      if ($this->dbDumped === true) {
        try {

          $this->zip_progress->log(__('Adding database SQL file(s) to the backup file.', 'backup-backup'), 'STEP');

          $files = [];

          if ($database_file !== false && !($this->db_exporter_files && sizeof($this->db_exporter_files) > 0)) {
            $files[] = $database_file;
          }

          $this->zip_progress->log('Database files in total found: ' . sizeof($this->db_exporter_files), 'VERBOSE');
          if ($this->db_exporter_files && sizeof($this->db_exporter_files) > 0) {
            for ($i = 0; $i < sizeof($this->db_exporter_files); ++$i) {
              $files[] = $this->db_exporter_files[$i];
            }
          } else {
            $this->zip_progress->log(__('No database files found to be added into backup, ignore this message if database was not meant to be included.', 'backup-backup'), 'WARN');
            $this->zip_progress->log('No database files found to be added into backup, ignore this message if database was not meant to be included.', 'VERBOSE');
          }
          
          $files = array_filter($files, function ($path) {
            if (is_readable($path) && is_writable($path) && !is_link($path)) return true;
            else {
              $this->zip_progress->log(sprintf(__("Excluding file that cannot be read: %s", 'backup-backup'), $path), 'warn');
              return false;
            }
          });
          
          if (sizeof($files) > 0) {
            if ($zipArchive) {
              for ($i = 0; $i < sizeof($files); ++$i) {
                
                // Add the file
                if (is_dir($files[$i])) {
                  $zip->addEmptyDir($this->cutDir($files[$i]));
                } else {
                  $zip->addFile($files[$i], $this->cutDir($files[$i]));
                }
                
              }
              
              $zAresult = $zip->close();
              if ($zAresult !== true) {
                $this->zip_progress->log('not_enough_space', 'verbose');
                $this->zip_failed('Error, there is most likely not enough space for the backup.');
                return false;
              
              }
              $zip->open($this->new_file_path);
            } else {
              $dbback = $lib->add($files, PCLZIP_OPT_REMOVE_PATH, $database_file_dir, PCLZIP_OPT_TEMP_FILE_THRESHOLD, $safe_limit);

              if ($dbback == 0) {
                $this->zip_failed($lib->errorInfo(true));
                return false;
              }
            }
          }

        } catch (\Exception $e) {
          $this->zip_failed($e->getMessage());

          return false;
        } catch (\Throwable $e) {
          $this->zip_failed($e->getMessage());

          return false;
        }
        
        if (sizeof($files) > 0) {
          $this->zip_progress->log(__('Database added to the backup successfully.', 'backup-backup'), 'SUCCESS');
        } else {
          $this->zip_progress->log(__('Database was not added to the backup.', 'backup-backup'), 'WARN');
        }
      }

      $this->zip_progress->log(__('Performing site files backup...', 'backup-backup'), 'STEP');

      try {
        $splitby = 200; $milestoneby = 500;
        $filestotal = sizeof($this->org_files);
        if ($filestotal < 3000) { $splitby = 250; $milestoneby = 500; }
        if ($filestotal > 5000) { $splitby = 500; $milestoneby = 500; }
        if ($filestotal > 10000) { $splitby = 1000; $milestoneby = 1000; }
        if ($filestotal > 15000) { $splitby = 2000; $milestoneby = 2000; }
        if ($filestotal > 20000) { $splitby = 4000; $milestoneby = 4000; }
        if ($filestotal > 25000) { $splitby = 6000; $milestoneby = 6000; }
        if ($filestotal > 30000) { $splitby = 8000; $milestoneby = 8000; }
        if ($filestotal > 32000) { $splitby = 10000; $milestoneby = 10000; }
        if ($filestotal > 60500) { $splitby = 20000; $milestoneby = 20000; }
        if ($filestotal > 90500) { $splitby = 40000; $milestoneby = 40000; }

        $this->zip_progress->log(__("Chunks contain ", 'backup-backup') . $splitby . __(" files.", 'backup-backup'));

        $chunks = array_chunk($this->org_files, $splitby);
        $chunkslen = sizeof($chunks);
        if ($chunkslen > 0) {
          $sizeoflast = sizeof($chunks[$chunkslen - 1]);
          if ($chunkslen > 1 && $sizeoflast == 1) {
            $buffer = array_slice($chunks[$chunkslen - 2], -1);
            $chunks[$chunkslen - 2] = array_slice($chunks[$chunkslen - 2], 0, -1);
            $chunks[$chunkslen - 1][] = $buffer[0];
          }
        }
        
        $backupSize = 0;
        for ($i = 0; $i < $chunkslen; ++$i) {

          // Abort if user wants it (check every 100 files)
          if (file_exists(BMI_BACKUPS . '/.abort')) {
            break;
          }
          
          $back = 0;
          $chunk = $chunks[$i];
          $chunk = array_filter($chunk, function ($path) {
            if (is_readable($path) && file_exists($path) && !is_link($path)) return true;
            else {
              $this->zip_progress->log(sprintf(__("Excluding file that cannot be read: %s", 'backup-backup'), $path), 'warn');
              return false;
            }
          });
          
          if (sizeof($chunk) > 0) {
            $needManipulation = false;
            if (strpos(WP_CONTENT_DIR, ABSPATH) === false) {
              $needManipulation = true;
            }
            if ($zipArchive) {
              for ($j = 0; $j < sizeof($chunk); ++$j) {

                if ($needManipulation) {
                  if (strpos($chunk[$j], WP_CONTENT_DIR) !== false) {
                    $path = 'wordpress' . DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR . substr($chunk[$j], strlen(WP_CONTENT_DIR));
                  } else {
                    $path = 'wordpress' . DIRECTORY_SEPARATOR . substr($chunk[$j], strlen(ABSPATH));
                  }
                } else {
                  $path = 'wordpress' . DIRECTORY_SEPARATOR . substr($chunk[$j], strlen(ABSPATH));
                }
                
                // Add the file
                $path = BMP::fixSlashes($path);
                if (is_dir($chunk[$j])) {
                  $zip->addEmptyDir($path);
                } else {
                  $zip->addFile($chunk[$j], $path);
                }
                
              }
              
              $zAresult = $zip->close();
              if ($zAresult !== true) {
                $this->zip_progress->log('not_enough_space', 'verbose');
                $this->zip_failed('Error, there is most likely not enough space for the backup.');
                return false;
              
              }
              $zip->open($this->new_file_path);
            } else {
            
              if ($needManipulation) {
                $abs = BMP::fixSlashes(ABSPATH) . DIRECTORY_SEPARATOR;
                $content = BMP::fixSlashes(WP_CONTENT_DIR) . DIRECTORY_SEPARATOR;
                $coreFiles = [];
                $contentFiles = [];
                foreach ($chunk as $file) {
                  if (strpos($file, $content) !== false) {
                    $contentFiles[] = $file;
                  } else {
                    $coreFiles[] = $file;
                  }
                }
                
                $back_1 = $lib->add($coreFiles, PCLZIP_OPT_REMOVE_PATH, $abs, PCLZIP_OPT_ADD_PATH, 'wordpress' . DIRECTORY_SEPARATOR, PCLZIP_OPT_ADD_TEMP_FILE_ON, PCLZIP_OPT_TEMP_FILE_THRESHOLD, $safe_limit);
                $back_2 = $lib->add($contentFiles, PCLZIP_OPT_REMOVE_PATH, $content, PCLZIP_OPT_ADD_PATH, 'wordpress' . DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR, PCLZIP_OPT_ADD_TEMP_FILE_ON, PCLZIP_OPT_TEMP_FILE_THRESHOLD, $safe_limit);
                $back = $back_1 && $back_2;
                
              } else {
                $back = $lib->add($chunk, PCLZIP_OPT_REMOVE_PATH, ABSPATH, PCLZIP_OPT_ADD_PATH, 'wordpress' . DIRECTORY_SEPARATOR, PCLZIP_OPT_ADD_TEMP_FILE_ON, PCLZIP_OPT_TEMP_FILE_THRESHOLD, $safe_limit);
              }
              if ($back == 0) {
                $this->zip_failed($lib->errorInfo(true));
                return false;
              }
              
            }
          }

          $curfile = (($i * $splitby) + $splitby);
          $this->zip_progress->progress($curfile . '/' . $max);
          if ($curfile % $milestoneby === 0 && $curfile < $max) {
            if (!file_exists($this->new_file_path)) 
              return $this->zip_failed('Expected backup file does not exist, there could be some issue or the backup was removed by third parties.');

            $currentBackupSize = filesize($this->new_file_path);
            if ($backupSize > $currentBackupSize)
              return $this->zip_failed('Backup size is smaller than it should be, it has been damaged, it may not be restorable, abort recommended.');
            
            $backupSize = $currentBackupSize;
            
            $this->zip_progress->log(__("Milestone: ", 'backup-backup') . ($curfile . '/' . $max), 'info');
          }
        }

      } catch (\Exception $e) {
        $this->zip_failed($e->getMessage());

        return false;
      } catch (\Throwable $e) {
        $this->zip_failed($e->getMessage());

        return false;
      }

      if (file_exists(BMI_BACKUPS . DIRECTORY_SEPARATOR . '.abort')) {

        $this->zip_progress->log('#002', 'END-CODE');
        
        if (file_exists($database_file_dir . 'bmi_backup_manifest.json')) {
          @unlink($database_file_dir . 'bmi_backup_manifest.json');
        }
        if (file_exists($database_file_dir . 'bmi_logs_this_backup.log')) {
          @unlink($database_file_dir . 'bmi_logs_this_backup.log');
        }

      } else {

        // End
        $this->zip_progress->log(__("Milestone: ", 'backup-backup') . ($max . '/' . $max), 'info');
        $this->zip_progress->log(__("Compressed ", 'backup-backup') . $max . __(" files", 'backup-backup'), 'SUCCESS');

        // Log time of ZIP Process
        $this->zip_progress->log(__("Archiving of ", 'backup-backup') . $max . __(" files took: ", 'backup-backup') . number_format(microtime(true) - $this->start_zip, 2) . 's');

        $this->zip_progress->log(__("Finalizing backup", 'backup-backup'), 'STEP');
        $this->zip_progress->log(__("Generating manifest file and saving current live-log...", 'backup-backup'), 'INFO');

        file_put_contents($database_file_dir . 'bmi_backup_manifest.json', $this->zip_progress->createManifest($dbBackupEngine));
        file_put_contents($database_file_dir . 'bmi_logs_this_backup.log', file_get_contents(BMI_BACKUPS . DIRECTORY_SEPARATOR . 'latest.log'));
        
        sleep(1);

        $files = [];

        if (file_exists($database_file_dir . 'bmi_logs_this_backup.log')) $files[] = $database_file_dir . 'bmi_logs_this_backup.log';
        if (file_exists($database_file_dir . 'bmi_backup_manifest.json')) $files[] = $database_file_dir . 'bmi_backup_manifest.json';
        else {

          $this->zip_failed('Manifest file could not be added, manifest does not exist.');
          return false;

        }
        
        $this->zip_progress->log(__("Adding manifest...", 'backup-backup'), 'INFO');
        try {

          if ($zipArchive) {
            for ($i = 0; $i < sizeof($files); ++$i) {
              
              // Add the file
              if (is_dir($files[$i])) {
                $zip->addEmptyDir($this->cutDir($files[$i]));
              } else {
                $zip->addFile($files[$i], $this->cutDir($files[$i]));
              }
              
            }
            
            $zAresult = $zip->close();
            if ($zAresult !== true) {
              $this->zip_progress->log('not_enough_space', 'verbose');
              $this->zip_failed('Error, there is most likely not enough space for the backup.');
              return false;
            
            }
          } else {
          
            $maback = $lib->add($files, PCLZIP_OPT_REMOVE_PATH, $database_file_dir, PCLZIP_OPT_ADD_TEMP_FILE_ON, PCLZIP_OPT_TEMP_FILE_THRESHOLD, $safe_limit);

            if ($maback == 0) {
              $this->zip_failed($lib->errorInfo(true));
              return false;
            }
            
          }

        } catch (\Exception $e) {
          $this->zip_failed($e->getMessage());

          return false;
        } catch (\Throwable $e) {
          $this->zip_failed($e->getMessage());

          return false;
        }

        if (file_exists($database_file_dir . 'bmi_backup_manifest.json')) {
          @unlink($database_file_dir . 'bmi_backup_manifest.json');
        }
        if (file_exists($database_file_dir . 'bmi_logs_this_backup.log')) {
          @unlink($database_file_dir . 'bmi_logs_this_backup.log');
        }

        $this->zip_progress->progress($max . '/' . $max);
        
        $this->zip_progress->log(__("Manifest file and logs added to the backup. Temporary files removed.", 'backup-backup'), 'SUCCESS');

      }
    }
    
    $this->zip_progress->log(__("Closing files and archives", 'backup-backup'), 'STEP');

    // Remove Better DB SQL Files
    if ($this->db_exporter_files && sizeof($this->db_exporter_files) > 0) {
      for ($i = 0; $i < sizeof($this->db_exporter_files); ++$i) {
        if (file_exists($this->db_exporter_files[$i])) @unlink($this->db_exporter_files[$i]);
      }
      if (file_exists($better_database_files_dir) && is_dir($better_database_files_dir)) {
        @rmdir($better_database_files_dir);
      }
    }

    if ($database_file && file_exists($database_file)) @unlink($database_file);
    if (!file_exists($this->new_file_path)) {
      $this->zip_failed('PHP-ZIP: After doing the zipping file can not be found');
    }
    if (filesize($this->new_file_path) === 0) {
      $this->zip_failed('PHP-ZIP: After doing the zipping file size is still 0 bytes');
    }

    // empty the array
    $this->org_files = [];
    
    $this->zip_progress->log(__("Cleanup finished, backup complete.", 'backup-backup'), 'SUCCESS');
    // $this->zip_progress->log('#001', 'END-CODE');
    return true;
    
  }

  public function zip_files($files, $to) {
    $this->zip_start($to);
    $this->zip_add($files);

    return $this->zip_end();
  }

  public function unzip_file($file_path, $target_dir = null, &$zip_progress = null) {

    // Progress
    $this->zip_progress = $zip_progress;

    // if it doesn't exist
    if (!file_exists($file_path)) {
      throw new \Exception("PHP-ZIP: File doesn't Exist");
    }

    $this->extr_file = $file_path;

    // if (class_exists("ZipArchive")) $this->lib = 1;
    // else $this->lib = 2;
    $this->lib = 2;

    if ($target_dir !== null) {
      return $this->unzip_to($target_dir);
    } else {
      return true;
    }
  }

  public function extract_files($zip_path, $files, $target_dir = null, &$zip_progress = null, $isFirstExtract = true) {

    $this->zip_progress = $zip_progress;

    // it exists, but it's not a directory
    if (file_exists($target_dir) && (!is_dir($target_dir))) {
      throw new \Exception("PHP-ZIPv2: Target directory exists as a file not a directory");
    }
    // it doesn't exist
    if (!file_exists($target_dir)) {
      if (!mkdir($target_dir)) {
        throw new \Exception("PHP-ZIPv2: Directory not found, and unable to create it");
      }
    }
    // validations -- end //
    // TODO: NOTICE: Force disable PCLZIP
    if (class_exists("ZipArchive") || class_exists("\ZipArchive")) {

      $zip = new \ZipArchive;
      $res = $zip->open($zip_path);

      if ($res === true) {

        if ($isFirstExtract) {
          $this->zip_progress->log(__("Using ZipArchive, omiting memory limit calculations...", 'backup-backup'), 'INFO');
        }

        $zip->extractTo($target_dir, $files);
        $zip->close();
        return true;

      } else {

        $this->restore_failed('PHP-ZIPv2: Could not open Backup with ZipArchive.');
        return false;

      }

    } else {

      if ($isFirstExtract) {
        $this->zip_progress->log(__("ZipArchive is not available, using PclZIP.", 'backup-backup'), 'INFO');
      }

      $safe_limit = $this->smartMemory($isFirstExtract);
      $this->loadPclZip($isFirstExtract);
      $lib = new \PclZip($zip_path);
      $restor = $lib->extract(PCLZIP_OPT_BY_NAME, $files, PCLZIP_OPT_PATH, $target_dir, PCLZIP_OPT_TEMP_FILE_THRESHOLD, $safe_limit);

      if ($restor == 0) {

        $this->restore_failed($lib->errorInfo(true));
        return false;

      }

      return true;

    }

  }

  public function loadPclZip($isFirstExtract = true) {

    if (!class_exists('PclZip')) {
      if (!defined('PCLZIP_TEMPORARY_DIR')) {
        $bmi_tmp_dir = BMI_TMP;
        if (!file_exists($bmi_tmp_dir)) {
          @mkdir($bmi_tmp_dir, 0775, true);
        }
        define('PCLZIP_TEMPORARY_DIR', $bmi_tmp_dir . DIRECTORY_SEPARATOR . 'bmi-');
      }

      if (defined('BMI_PRO_PCLZIP') && file_exists(BMI_PRO_PCLZIP)) {
        require_once BMI_PRO_PCLZIP;
        if ($isFirstExtract) {
          $this->zip_progress->log(__('Using dedicated PclZIP for Premium Users.', 'backup-backup'), 'INFO');
        }
      } else {
        require_once trailingslashit(ABSPATH) . 'wp-admin/includes/class-pclzip.php';
      }
    }

  }

  public function smartMemory($isFirstExtract = true) {

    // Smart memory -- start //
    if ($this->zip_progress != null && $isFirstExtract) {
      $this->zip_progress->log(__("Smart memory calculation...", 'backup-backup'), 'STEP');
    }

    if ((intval($this->return_bytes(ini_get('memory_limit'))) / 1024 / 1024) < 384) {
      @ini_set('memory_limit', '384M');
    }

    $memory_limit = (intval($this->return_bytes(ini_get('memory_limit'))) / 1024 / 1024);
    if ($this->zip_progress != null && $isFirstExtract) {
      $this->zip_progress->log(str_replace('%s', $memory_limit, __("There is %s MBs of memory to use", 'backup-backup')), 'INFO');
    }

    $safe_limit = intval($memory_limit / 4);
    if ($safe_limit > 64) $safe_limit = 64;
    if ($memory_limit === 384) $safe_limit = 78;
    if ($memory_limit >= 512) $safe_limit = 104;
    if ($memory_limit >= 1024) $safe_limit = 228;
    if ($memory_limit >= 2048) $safe_limit = 428;

    // $real_memory = intval(memory_get_usage() * 0.9 / 1024 / 1024);
    // if ($real_memory < $safe_limit) $safe_limit = $real_memory;
    $safe_limit = intval($safe_limit * 0.8);

    if ($this->zip_progress != null && $isFirstExtract) {
      $this->zip_progress->log(str_replace('%s', $safe_limit, __("Setting the safe limit to %s MB", 'backup-backup')), 'SUCCESS');
    }
    // Smart memory -- end //

    return $safe_limit;

  }

  public function unzip_to($target_dir) {

        // validations -- start //
    if ($this->lib === 0 && $this->extr_file === 0) {
      throw new \Exception("PHP-ZIP: unzip_file hasn't been called");
    }
    // it exists, but it's not a directory
    if (file_exists($target_dir) && (!is_dir($target_dir))) {
      throw new \Exception("PHP-ZIP: Target directory exists as a file not a directory");
    }
    // it doesn't exist
    if (!file_exists($target_dir)) {
      if (!mkdir($target_dir)) {
        throw new \Exception("PHP-ZIP: Directory not found, and unable to create it");
      }
    }
    // validations -- end //

    // Target Directory
    $this->extr_dirc = $target_dir;

    // Smart Memory
    $safe_limit = $this->smartMemory();

    // Extract msg
    $this->zip_progress->log(__('Extracting files into temporary directory (this process can take some time)...', 'backup_migration'), 'STEP');

    // Force PCL Zip
    $this->lib = 2;

    // extract using ZipArchive
    // if($this->lib === 1) {
    // 	$lib = new \ZipArchive;
    // 	if(!$lib->open($this->extr_file)) throw new \Exception("PHP-ZIP: Unable to open the zip file");
    // 	if(!$lib->extractTo($this->extr_dirc)) throw new \Exception("PHP-ZIP: Unable to extract files");
    // 	$lib->close();
    // }

    // extarct using PclZip
    if ($this->lib === 2) {
      $this->loadPclZip();
      $lib = new \PclZip($this->extr_file);
      $restor = $lib->extract(PCLZIP_OPT_PATH, $this->extr_dirc, PCLZIP_OPT_TEMP_FILE_THRESHOLD, $safe_limit);
      if ($restor == 0) {
        $this->restore_failed($lib->errorInfo(true));

        return false;
      }
    }

    return true;
  }

  private function dir_to_assoc_arr(DirectoryIterator $dir) {
    $data = [];
    foreach ($dir as $node) {
      if ($node->isDir() && !$node->isDot()) {
        $data[$node->getFilename()] = $this->dir_to_assoc_arr(new DirectoryIterator($node->getPathname()));
      } elseif ($node->isFile()) {
        $data[] = $node->getFilename();
      }
    }

    return $data;
  }

  private function path() {
    return join(DIRECTORY_SEPARATOR, func_get_args());
  }

  private function commonPath($files, $remove = true) {
    foreach ($files as $index => $filesStr) {
      $files[$index] = explode(DIRECTORY_SEPARATOR, $filesStr);
    }
    $toDiff = $files;
    foreach ($toDiff as $arr_i => $arr) {
      foreach ($arr as $name_i => $name) {
        $toDiff[$arr_i][$name_i] = $name . "___" . $name_i;
      }
    }
    $diff = call_user_func_array("array_diff", $toDiff);
    reset($diff);
    $i = key($diff) - 1;
    if ($remove) {
      foreach ($files as $index => $arr) {
        $files[$index] = implode(DIRECTORY_SEPARATOR, array_slice($files[$index], $i));
      }
    } else {
      foreach ($files as $index => $arr) {
        $files[$index] = implode(DIRECTORY_SEPARATOR, array_slice($files[$index], 0, $i));
      }
    }

    return $files;
  }
}
