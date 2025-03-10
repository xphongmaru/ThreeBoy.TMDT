(function ($) {
    jQuery("document").ready(function () {
        jQuery(document).on('click', ".tp-preset-editor-raw", function (event) {
            let id = event.target?.dataset?.temp_id;

            jQuery.ajax({
                url: tp_wdkit_preview_popup.ajax_url,
                dataType: 'json',
                type: "post",
                async: true,
                data: {
                    action: 'check_plugin_status',
                    security: tp_wdkit_preview_popup.nonce,
                },
                success: function (res) {

                    if (res?.installed) {
                        var e;
                        if (!e && id) {
                            window.WdkitPopup = elementorCommon.dialogsManager.createWidget("lightbox", {
                                id: "wdkit-elementor",
                                className: 'wkit-contentbox-modal wdkit-elementor',
                                headerMessage: !1,
                                message: "",
                                hide: {
                                    auto: !1,
                                    onClick: !1,
                                    onOutsideClick: !1,
                                    onOutsideContextMenu: !1,
                                    onBackgroundClick: !0
                                },
                                position: {
                                    my: "center",
                                    at: "center"
                                },
                                onShow: function () {
                                    var e = window.WdkitPopup.getElements("content");
                                    window.location.hash = '#/preset/' + id;
                                    window.WdkitPopupToggle.open({ route: "/preset/" + id }, e.get(0), "elementor")
                                },
                                onHide: function () {
                                    var e = window.WdkitPopup.getElements("content");
                                    window.WdkitPopupToggle.close(e.get(0)), window.WdkitPopup.destroy()
                                }
                            }),
                                window.WdkitPopup.getElements("header").remove(), window.WdkitPopup.getElements("message").append(window.WdkitPopup.addElement("content"))
                        }
                        return window.WdkitPopup.show()
                    } else {
                        window.tp_wdkit_editor = elementorCommon.dialogsManager.createWidget(
                            "lightbox",
                            {
                                id: "tp-wdkit-elementorp",
                                headerMessage: !1,
                                message: "",
                                hide: {
                                    auto: !1,
                                    onClick: !1,
                                    onOutsideClick: false,
                                    onOutsideContextMenu: !1,
                                    onBackgroundClick: !0,
                                },
                                position: {
                                    my: "center",
                                    at: "center",
                                },
                                onShow: function () {
                                    var dialogLightboxContent = $(".dialog-lightbox-message"),
                                        clonedWrapElement = $("#tp-wdkit-wrap");
                                    window.location.hash = '#/preset/' + id;

                                    clonedWrapElement = clonedWrapElement.clone(true).show()
                                    dialogLightboxContent.html(clonedWrapElement);

                                    dialogLightboxContent.on("click", ".tp-close-btn", function () {
                                        window.tp_wdkit_editor.hide();
                                    });
                                },
                                onHide: function () {
                                    window.tp_wdkit_editor.destroy();
                                }
                            }
                        );

                        $(document).on('click', '.tp-wdesign-install', function (e) {
                            e.preventDefault();

                            var $button = $(this);
                            var $loader = $button.find('.tp-wb-loader-circle');
                            var $text = $button.find('.theplus-enable-text');

                            $loader.css('display', 'block');

                            jQuery.ajax({
                                url: tp_wdkit_preview_popup.ajax_url,
                                dataType: 'json',
                                type: "post",
                                async: true,
                                data: {
                                    action: 'tp_install_wdkit',
                                    security: tp_wdkit_preview_popup.nonce,
                                },
                                success: function (res) {

                                    $loader.css('display', 'none');

                                    if (true === res.success) {
                                        elementor.saver.update.apply().then(function () {
                                            window.location.hash = window.location.hash + '?wdesignkit=open'
                                            window.location.reload();
                                        });

                                    } else {
                                        $text.text(ENABLE_TEMPLATES_TEXT);
                                    }
                                },
                                error: function () {
                                    $loader.css('display', 'none');
                                    $text.css('display', 'block').text(ENABLE_TEMPLATES_TEXT);
                                }
                            });
                        });

                        return window.tp_wdkit_editor.show();
                    }
                },
                error: function (res) {
                }
            });
        });
    });
})(jQuery);