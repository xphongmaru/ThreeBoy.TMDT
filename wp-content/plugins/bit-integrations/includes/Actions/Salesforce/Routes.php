<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitCode\FI\Actions\Salesforce\SalesforceController;
use BitCode\FI\Core\Util\Route;

Route::post('selesforce_generate_token', [SalesforceController::class, 'generateTokens']);
Route::post('selesforce_custom_action', [SalesforceController::class, 'customActions']);
Route::post('selesforce_campaign_list', [SalesforceController::class, 'selesforceCampaignList']);
Route::post('selesforce_lead_list', [SalesforceController::class, 'selesforceLeadList']);
Route::post('selesforce_contact_list', [SalesforceController::class, 'selesforceContactList']);
Route::post('selesforce_custom_field', [SalesforceController::class, 'customFields']);

Route::post('selesforce_account_list', [SalesforceController::class, 'selesforceAccountList']);
Route::post('selesforce_case_origin', [SalesforceController::class, 'selesforceCaseOrigin']);
Route::post('selesforce_case_type', [SalesforceController::class, 'selesforceCaseType']);
Route::post('selesforce_case_reason', [SalesforceController::class, 'selesforceCaseReason']);
Route::post('selesforce_case_status', [SalesforceController::class, 'selesforceCaseStatus']);
Route::post('selesforce_case_priority', [SalesforceController::class, 'selesforceCasePriority']);
Route::post('selesforce_case_potential_liability', [SalesforceController::class, 'selesforceCasePotentialLiability']);
Route::post('selesforce_case_sla_violation', [SalesforceController::class, 'selesforceCaseSLAViolation']);
