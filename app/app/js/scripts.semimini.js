var ajaxUrl = "";
function resizeActions() {
    var t = ($(".sub_header").width() - $(".sub_header div").outerWidth()) / 2;
    $(".contentMainLeft").css("padding-left", t)
}
function initFunctions() {
    $(document).on("click", ".main_menu_navigation li a:not(.dropdown-toggle):not(.exportToPDF),.logo_container", function(t) {
        t.preventDefault(),
		_this = this;
        easyAjax({
            url: $(this).attr("href"),
            targets: {
                ".contentMainLeft": ".contentMainLeft",
                ".sub_header_inner": ".sub_header_inner",
                ".activityBar_inner": ".activityBar_inner"
            },
            preAjaxFunc: function() {
                animateFullPageOut();
				setClickedHref($(_this).attr("href"));
            },
            postAjaxFunc: function() {
                animateFullPageIn()
            }
        })
    }),
    $(document).on("click", ".pagination li a", function(t) {
        t.preventDefault(),
        easyAjax({
            url: $(this).attr("href"),
            targets: {
                ".contentMainLeft": ".contentMainLeft"
            },
            preAjaxFunc: function() {
                $(".contentMainLeft").animate({
                    opacity: 0
                }, 500)
            },
            postAjaxFunc: function() {
                $(".selectpicker").selectpicker(),
                $(".contentMainLeft").animate({
                    opacity: 1
                }, 500),
                $("html, body").animate({
                    scrollTop: 0
                }, 500)
            }
        })
    }),
    initTextAreTokenInsertion(),
    $(".selectpicker").selectpicker(),
    $(".activity_toggle").click(function() {
        activintyToggle()
    }),
    $("#currency_id").length && (currencySelect($("#currency_id")[0]),
    $("#currency_id").change(function() {
        currencySelect(this)
    })),
    $(document).on("change", "#selectUnselect_all_cb", function() {
        $(".bulkItemRow_cb").each($(this).is(":checked") ? function() {
            this.checked = !0
        }
        : function() {
            this.checked = !1
        }
        )
    }),
    $(".datepicker").datepicker({
		format: "yyyy-mm-dd",
        todayBtn: "linked"
    }),
    $(document).on("click", ".open-modal", function(t) {
        t.preventDefault(),
        openModal(t, this)
    }),
    $(document).on("click", ".open-statement-send", function(t) {
        t.preventDefault(),
        openSendStatement(t, this)
    }),
    $(document).on("click", ".openImageModal", function(t) {
        t.preventDefault(),
        initImageModal(t)
    }),
    $(document).on("click", ".open-reminder-modal", function(t) {
        t.preventDefault(),
        openModal(t, this)
    }),
    $(document).on("click", ".open_add_sales_item_modal", function(t) {
        t.preventDefault(),
        $(this).attr("data-success-function", "updateSalesItem('" + $(this).closest(".itemRow").attr("id") + "',form);"),
        openModal(t, this)
    }),
    $("#main_modal").on("show.bs.modal", function() {}),
    $("#main_modal").on("hidden.bs.modal", function() {
        $(".modal-backdrop").removeClass("in"),
        setTimeout(function() {
            $(".modal-backdrop").remove()
        }, 300)
    }),
    $("#offline_error_modal").on("show.bs.modal", function(t) {
        {
            var e = $(t.relatedTarget)[0];
            $(this)
        }
        e.error_intro && $("#offline_error_modal").find("#error_intro").html(e.error_intro),
        e.error_type && $("#offline_error_modal").find("#error_type").html(e.error_type),
        e.error_action && $("#offline_error_modal").find("#error_action").html(e.error_action),
        e.persistant_error_action && $("#offline_error_modal").find("#persistant_error_action").html(e.persistant_error_action)
    }),
    $(".addItemRow").on("click", function() {
        var t = $(".itemRow").length;
        $.ajax({
            url: base_url + "snippets/item/add/" + t,
            method: "GET",
            headers: {
                Auth: getCookie("auth"),
                Session: getCookie("session_id"),
                "Account-Name": getCookie("account")
            }
        }).done(function(t) {
            $(".listSubTotal").before(t),
            $(".selectpicker").selectpicker(),
            calculateInvoiceItemAmounts()
        })
    }),
    $(document).on("keyup", "#discount_percentage", function() {
        calculateInvoiceItemAmounts()
    }),
    $(document).on("keyup", "#headerSearchInput", function() {
        startSearch(this)
    }),
    $(document).on("blur", "#headerSearchInput", function() {
        $(this).val("")
    }),
    $(document).on("keyup", ".ajax_ListItemSearch", function() {
        startListItemSearch(this)
    }),
    $(document).on("click", ".saveFormData", function() {
        $(this).attr("disabled", "disabled"),
        $(this).addClass("loader"),
        $(this.form).data($(this).data()),
        $(this.form).submit()
    }),
    $(document).on("click", ".docMarkStatus", function(t) {
        t.preventDefault(),
        markDocStatus(t, this)
    }),
    $(document).on("submit", "form:not(#dateFilter, .login_form, .forgot_pass_form, .send_forgot_pass_form, #signup_form, #verification_form)", function(t) {
        t.preventDefault(),
        saveFormData($(this))
    }),
    $(document).on("click", ".print", function(t) {
        t.preventDefault(),
        window.print()
    }),
    $(document).on("click", ".statementFilter", function(t) {
        t.preventDefault(),
        filterStatement(t, $(this))
    }),
    $(".itemList").length && calculateInvoiceItemAmounts(),
    $(document).on("change", ".sort_by_dd", function() {
        window.location = $(this).val()
    }),
    $(document).on("click", "#saveReminder", function(t) {
        setReminderValues(t)
    }),
    $(document).on("click", ".markAsSent", function(t) {
        changeStatus(t)
    }),
    $(document).on("click", ".single_archive", function(t) {
        archive(t)
    }),
    $(document).on("click", ".exportToPDF", function(t) {
        exportToPDF(t)
    }),
    $(document).on("change", ".payInFullCheckbox", function() {
        var t = $(this).parent().parent().parent().find(".payment_amount")
          , e = $(this).parent().parent().parent().find(".useCreditCheckbox");
        $(this).is(":checked") ? (t.data("originalAmount", t.val()),
        t.val($(e).is(":checked") ? t.data("outstandingAmount") > t.data("creditAmount") ? t.data("outstandingAmount") - t.data("creditAmount") : "0.00" : t.data("outstandingAmount"))) : t.val(t.data("originalAmount"))
    }),
    $(document).on("change", ".useCreditCheckbox", function() {
        var t = $(this).parent().parent().parent().find(".payment_amount")
          , e = $(this).parent().parent().parent().find(".payInFullCheckbox");
        t.val($(this).is(":checked") ? $(e).is(":checked") ? t.data("outstandingAmount") > t.data("creditAmount") ? t.data("outstandingAmount") - t.data("creditAmount") : "0.00" : t.data("originalAmount") : t.data($(e).is(":checked") ? "outstandingAmount" : "originalAmount"))
    }),
    $(document).on("keyup", "#payment_amount", function() {
        var t = $(this).val()
          , e = commaSeperateNumber(t)
          , i = $(this).parent().parent().parent().find(".payInFullCheckbox");
        parseFloat(t) >= parseFloat($(this).data("outstandingAmount")) ? (i[0].checked = !0,
        i.attr("checked", "checked")) : (i[0].checked = !1,
        i.removeAttr("checked")),
        $(this).val(e)
    }),
    $(document).on("change", "#bulkActions", function(t) {
        showBulkModal(t)
    }),
    $(document).on("keyup", "#account_url", function(t) {
        changeAccountName(t)
    }),
    $(document).on("change", "#logoFile", function(t) {
        imageToBase64(t)
    }),
    $(document).on("change", "#recieptFile", function(t) {
        recieptToBase64(t)
    }),
    $(document).on("change", "#previewLogoFile", function(t) {
        saveLogoFromPreview(t)
    }),
    $(document).on("submit", ".login_form", function(t) {
        t.preventDefault(),
        boostLogin(t)
    }),
    $(document).on("click", ".logout_button", function(t) {
        t.preventDefault(),
        boostLogout(t)
    }),
    $(document).on("submit", ".forgot_pass_form", function(t) {
        t.preventDefault(),
        passwordReset(t, $(this))
    }),
    $(document).on("submit", ".send_forgot_pass_form", function(t) {
        t.preventDefault(),
        sendPasswordReset(t, $(this))
    }),
    $(document).on("submit", "#signup_form", function(t) {
        t.preventDefault(),
        boostRegister(t)
    }),
    $(document).on("submit", "#verification_form", function(t) {
        t.preventDefault(),
        boostVerify(t)
    }),
    $(document).on("click", ".login_alert_close", function(t) {
        t.preventDefault(),
        $(".login_alert_container").find(".alert").animate({
            opacity: 0
        }, 400, function() {
            $(".login_alert_container").slideUp(300)
        })
    }),
    $(document).ajaxError(function(t, e, i, n) {
        ajaxErrorActions(t, e, i, n)
    }),
    $(document).on("click", ".remove_logo_button", function(t) {
        removeSettingsLogo(t)
    }),
    $(document).on("click", "#remove_reciept", function(t) {
        removeReciept(t)
    }),
    $(document).on("click", ".theme_instance", function(t) {
        setTheme(t)
    })
}
function openModal(t, e) {
    var i = $(e)
      , n = $("#main_modal")
      , a = {};
    if ("undefined" != typeof i.attr("href")) {
        var r = i.attr("href");
        "undefined" != typeof i[0].dataset && (a.form_dataset = {},
        $.each(i[0].dataset, function(t, e) {
            var i = "data-" + t.replace(/([a-z])([A-Z])/g, "$1-$2").toLowerCase();
            a.form_dataset[i] = e
        }))
    } else if ("undefined" != typeof i.attr("data-tokens"))
        var r = i.attr("data-tokens");
    else if ("undefined" != typeof i[0].modalUrl) {
        a = i[0];
        var r = i[0].modalUrl
    } else {
        t.preventDefault();
        var r = null
    }
    0 == $(".modal-backdrop").length && ($("body").append('<div class="modal-backdrop fade loading" rel-modal="main_modal"></div>'),
    setTimeout(function() {
        $(".modal-backdrop").addClass("in")
    }, 100)),
    $.ajax({
        url: r,
        method: "GET",
        data: a,
        headers: {
            Auth: getCookie("auth"),
            Session: getCookie("session_id"),
            "Account-Name": getCookie("account")
        }
    }).done(function(t) {
        $(".modal-backdrop").removeClass("loading"),
        "number" == typeof i[0].closeDelaySeconds && setTimeout(function() {
            $("#main_modal").modal("hide")
        }, 1e3 * i[0].closeDelaySeconds),
        n.html($(t).filter(".modal-dialog")),
        n.find(".selectpicker").selectpicker(),
        i[0].downloadFile && (window.location = i[0].downloadFile),
        $(".datepicker").datepicker({
            format: "yyyy-mm-dd",
            todayBtn: "linked"
        }),
        n.modal("show", e)
    })
}
function initImageModal(t) {
    $("#image_modal .modal-body").html('<img src="/images/logo_upload_waiting.gif"  />'),
    $("#image_modal").modal("show", this);
    var e = new Image;
    e.onload = function() {
        $("#image_modal .modal-body").html('<img class="modal_preview_image" src="' + e.src + '"/>')
    }
    ,
    e.src = $(t.currentTarget).data("base64_data") ? $(t.currentTarget).data("base64_data") : t.currentTarget.href
}
function ajaxErrorActions(t, e, i, n) {
    if ($(".saveButton,.saveFormData").removeAttr("disabled"),
    $(".saveButton,.saveFormData").removeClass("loader"),
    401 == e.status)
        if ($("form.login_form.landing").length)
            $(".login_alert_container .alert strong").text("You have entered the incorrect details"),
            $(".login_alert_container").slideDown(300, function() {
                $(this).find(".alert").animate({
                    opacity: 1
                }, 400)
            });
        else {
            var a = jQuery.parseJSON(e.responseText)
              , r = {};
            r.modalUrl = base_url + "modal/login",
            r.alertText = a.message,
            openModal(t = document, r)
        }
    else if ("408" == e.status) {
        var o = {
            error_type: "(" + e.status + ") " + n,
            error_action: "Please check your connection and try again."
        };
        $("#offline_error_modal").modal("show", o)
    } else {
        var o = {
            error_type: "(" + e.status + ") " + n,
            error_action: "Please try again."
        };
        $("#offline_error_modal").modal("show", o)
    }
}
function recieptToBase64(t) {
    var e = t.target;
    if (e.files && e.files[0]) {
        var i = new FileReader;
        i.onload = function(t) {
            $(".reciept_preview_container").html('<a id="view_reciept" class="action_links openImageModal" target="_blank">View</a> <span class="action_links">|</span> <a href="#" class="action_links" id="remove_reciept">Remove</a><input type="hidden" value="" id="reciept_image_data" name="image_string">'),
            $("#preview-logo").css("opacity", 0),
            $("#view_reciept").data("base64_data", t.target.result),
            $("#view_reciept").attr("href", "#"),
            $("#reciept_image_data").val(t.target.result),
            $(".reciept_preview_container").fadeIn(400)
        }
        ,
        i.readAsDataURL(e.files[0])
    }
}
function imageToBase64(t) {
    var e = t.target;
    if (e.files && e.files[0]) {
        var i = new FileReader;
        i.onload = function(t) {
            $(".logo_preview_container").html('<img class="preview-logo" id="preview-logo"><input type="hidden" id="logo_image_data" name="image_string"><div class="clearfix formSpacer"></div>'),
            $("#preview-logo").css("opacity", 0),
            $("#preview-logo").attr("src", t.target.result),
            $("#logo_image_data").val(t.target.result),
            $(".logo_preview_container").slideDown(400, function() {
                $("#preview-logo").animate({
                    opacity: 1
                }, 400),
                $(".remove_logo_button_container").fadeIn(400)
            })
        }
        ,
        i.readAsDataURL(e.files[0])
    }
}
function removeSettingsLogo(t) {
    t.preventDefault(),
    $(".remove_logo_button_container").fadeOut(400),
    $("#preview-logo").animate({
        opacity: 0
    }, 400, function() {
        $(".logo_preview_container").slideUp(400, function() {
            $("#preview-logo").remove(),
            $("#logo_image_data").val("")
        })
    })
}
function removeReciept(t) {
    t.preventDefault(),
    $(".reciept_preview_container").fadeOut(400, function() {
        $("#view_reciept").attr("href", ""),
        0 == $("#reciept_image_data").length ? $(".reciept_preview_container").append('<input type="hidden" name="image_string" id="reciept_image_data" value="">') : $("#reciept_image_data").val("")
    })
}
function setTheme(t) {
    $(".theme_instance.active").removeClass("active"),
    $(t.target).addClass("active"),
    $("#theme_id").val($(t.target).data("themeId"))
}
function saveLogoFromPreview(t) {
    var e = t.target;
    if (e.files && e.files[0]) {
        var i = new FileReader;
        $(".upload_button_container").replaceWith('<img class="preview-logo" id="preview-logo" src="' + base_url + 'images/logo_upload_waiting.gif">'),
        i.onload = function(t) {
            $.ajax({
                url: api_base_url + "theme_settings",
                method: "PUT",
                headers: {
                    Auth: getCookie("auth"),
                    Session: getCookie("session_id"),
                    "Account-Name": getCookie("account")
                },
                data: JSON.stringify({
                    image_string: t.target.result
                })
            }).done(function() {
                $("#preview-logo").animate({
                    opacity: 0
                }, 400, function() {
                    $("#preview-logo").attr("src", t.target.result),
                    $("#preview-logo").delay(200).animate({
                        opacity: 1
                    }, 400)
                })
            })
        }
        ,
        i.readAsDataURL(e.files[0])
    }
}
function boostVerify(t) {
    var e = $(t.target).find("#signup_token").val()
      , i = $(t.target).attr("action")
      , n = $(t.target).attr("method")
      , a = {
        signup_token: e
    };
    $.ajax({
        url: i,
        method: n,
        data: JSON.stringify(a),
        context: t
    }).done(function(t) {
        "OK" == t.status ? ($(".modal-buttons > *").animate({
            opacity: 0
        }, 400, function() {
            $(".modal-buttons").slideUp(300)
        }),
        $(".input_group > *").animate({
            opacity: 0
        }, 400, function() {
            $(".input_group").slideUp(300)
        }),
        $(".login_alert_container").find(".alert").animate({
            opacity: 0
        }, 400, function() {
            $(".login_alert_container").slideUp(300, function() {
                $(".register_success_container").slideDown(300, function() {
                    $(this).find(".alert").animate({
                        opacity: 1
                    }, 400, function() {
                        boostAutoLogin(t.data)
                    })
                })
            })
        })) : ($(".input_group > *").animate({
            opacity: 0
        }, 400, function() {
            $(".input_group").slideUp(300)
        }),
        $(".modal-buttons").fadeIn(400),
        $(".login_alert_container .alert strong").text(t.message[0]),
        $(".login_alert_container").slideDown(300, function() {
            $(this).find(".alert").animate({
                opacity: 1
            }, 400)
        }))
    }).error(function() {
        $(t.target).find(".modal-buttons.hidden").fadeIn(400),
        $(".login_alert_container").slideDown(300, function() {
            $(this).find(".alert").animate({
                opacity: 1
            }, 400)
        })
    })
}
function boostRegister(t) {
    $(t.currentTarget).find(".error").remove(),
    $(t.currentTarget).find(".input-danger").removeClass("input-danger");
    var e = $(t.target).find(".saveButton");
    e.attr("disabled", "disabled"),
    e.addClass("loader");
    var i = $(t.target).find("#email").val()
      , n = $(t.target).find("#company_name").val()
      , a = $(t.target).attr("action")
      , r = $(t.target).attr("method")
      , o = {
        email: i,
        company_name: n
    };
    $.ajax({
        url: a,
        method: r,
        data: JSON.stringify(o),
        context: t
    }).done(function(e) {
        var i = $(t.target).find(".saveButton");
        i.removeAttr("disabled"),
        i.removeClass("loader"),
        "OK" == e.status ? ($(".modal-buttons > *").animate({
            opacity: 0
        }, 400, function() {
            $(".modal-buttons").slideUp(300)
        }),
        $(".input_group > *").animate({
            opacity: 0
        }, 400, function() {
            $(".input_group").slideUp(300)
        }),
        $(".login_alert_container").find(".alert").animate({
            opacity: 0
        }, 400, function() {
            $(".login_alert_container").slideUp(300, function() {
                $(".register_success_container").slideDown(300, function() {
                    $(this).find(".alert").animate({
                        opacity: 1
                    }, 400)
                })
            })
        })) : ($.each(e.validation_results, function(e, i) {
            $(t.currentTarget).find('[name="' + e + '"]').each(function() {
                $(this).addClass("input-danger"),
                $(this).after('<div class="error">' + i + "</div>")
            })
        }),
        $(".login_alert_container .alert strong").text(e.message[0]),
        $(".login_alert_container").slideDown(300, function() {
            $(this).find(".alert").animate({
                opacity: 1
            }, 400)
        }))
    }).error(function() {
        var e = $(t.target).find(".saveButton");
        e.removeAttr("disabled"),
        e.removeClass("loader")
    })
}
function getUrlValue(t) {
    var e = $(location).attr("href").split("?")
      , i = "";
    if (2 == e.length) {
        e = e[1].split("&");
        for (var n = 0; n < e.length; n += 1) {
            var a = e[n].split("=");
            if (2 == a.length && a[0] == t) {
                i = a[1];
                break
            }
        }
    }
    return i
}
function getSubDomain(t) {
    t = t.replace(/https:\/\/|http:\/\/|www./gi, "");
    var e = t;
    if (-1 === t.indexOf("/"))
        var e = t;
    else
        var e = t.split("/")[0];
    return e.split("." + domain_name_string)[0]
}
function boostLogin(t) {
    var e = $(t.target).find(".saveButton");
    e.attr("disabled", "disabled"),
    e.addClass("loader");
    var i = $(t.target).find("#email").val()
      , n = $(t.target).find("#password").val()
      , a = $(t.target).attr("action")
      , r = getSubDomain(window.location.href)
      , o = {
        email: i,
        password: n,
        account_name: r,
        timestamp: $.now()
    }
      , s = Base64.encode(JSON.stringify(o))
      , l = {
        login: s
    };
    $.ajax({
        url: a,
        method: "POST",
        data: JSON.stringify(l),
        context: t,
        headers: {}
    }).done(function(e) {
        var i = $(t.target).find(".saveButton");
        i.removeAttr("disabled"),
        i.removeClass("loader"),
        "OK" == e.status && (setCookie("session_id", e.session_id),
        setCookie("auth", e.token),
        setCookie("account", r),
        $(this.currentTarget).hasClass("landing") ? window.location = "" != getUrlValue("redirAddress") ? decodeURIComponent(getUrlValue("redirAddress")) : base_url : $("#main_modal").modal("hide"))
		if(ajaxUrl != "")
			window.location = ajaxUrl;
	}).error(function() {
        var e = $(t.target).find(".saveButton");
        e.removeAttr("disabled"),
        e.removeClass("loader")
    })
}
function boostAutoLogin(t) {
    var e = {
        email: t.email,
        password: t.tmppass,
        account_name: t.account_name,
        timestamp: $.now()
    }
      , i = Base64.encode(JSON.stringify(e))
      , n = {
        login: i
    };
    $.ajax({
        url: api_base_url + "login",
        method: "POST",
        data: JSON.stringify(n)
    }).done(function(e) {
        if ("OK" == e.status) {
            setCookie("session_id", e.session_id),
            setCookie("auth", e.token),
            setCookie("account", t.account_name);
            var i = location.protocol + "//" + t.account_name + "." + main_domain
              , n = {
                openModal: {
                    modalUrl: i + "/modal/beta_notice/"
                }
            };
            window.location = i + "/settings/?" + $.param(n)
        }
    })
}
function boostLogout() {
    $.ajax({
        url: api_base_url + "login",
        method: "DELETE",
        headers: {
            Auth: getCookie("auth"),
            Session: getCookie("session_id"),
            "Account-Name": getCookie("account")
        }
    }).done(function(t) {
        if (setCookie("session_id", "", 0),
        setCookie("auth", "", 0),
        setCookie("account", "", 0),
        "OK" == t.status)
            window.location = base_url + "login?error=" + encodeURIComponent("You were successfully logged out.");
        else {
            var e = {
                error_type: "Logout Error",
                error_action: "The system could not log you out...Please try again."
            };
            $("#offline_error_modal").modal("show", e)
        }
    }).error(function() {
        var t = {
            error_type: "Logout Error",
            error_action: "The system could not log you out...Please try again."
        };
        $("#offline_error_modal").modal("show", t)
    })
}
function sendPasswordReset(t, e) {
    $(".saveButton").attr("disabled", "disabled");
    var i = e.attr("action")
      , n = e.attr("method")
      , a = {
        email: $(t.target).find("#email").val()
    };
    0 != $(".register_success_container").find(".alert").css("opacity") && $(".register_success_container").find(".alert").animate({
        opacity: 0
    }, 400, function() {
        $(".register_success_container").slideUp(300)
    }),
    0 != $(".login_alert_container").find(".alert").css("opacity") && $(".login_alert_container").find(".alert").animate({
        opacity: 0
    }, 400, function() {
        $(".login_alert_container").slideUp(300)
    }),
    $(t.currentTarget).find(".input-danger").removeClass("input-danger"),
    $.ajax({
        url: i,
        method: n,
        data: JSON.stringify(a),
        context: e,
        headers: {
            "Account-Name": getSubDomain(window.location.href)
        }
    }).done(function(e) {
        "OK" == e.status ? setTimeout(function() {
            var t = "<h2>Success!</h2>";
            $.each(e.message, function(e, i) {
                t += i + "<br/>"
            }),
            $(".register_success_container > .alert > strong").html(t),
            $(".login_alert_container").find(".alert").animate({
                opacity: 0
            }, 400, function() {
                $(".login_alert_container").slideUp(300, function() {
                    $(".register_success_container").slideDown(300, function() {
                        $(this).find(".alert").animate({
                            opacity: 1
                        }, 400)
                    })
                })
            }),
            $(".saveButton").removeAttr("disabled")
        }, 400) : setTimeout(function() {
            $.each(e.validation_results, function(e) {
                $(t.currentTarget).find('[name="' + e + '"]').each(function() {
                    $(this).addClass("input-danger")
                })
            });
            var i = e.message[0] + "<br/>";
            $(".login_alert_container > .alert > strong").html(i),
            $(".login_alert_container").slideDown(300, function() {
                $(this).find(".alert").animate({
                    opacity: 1
                }, 400)
            }),
            $(".saveButton").removeAttr("disabled")
        }, 400)
    })
}
function passwordReset(t, e) {
    $(".saveButton").attr("disabled", "disabled");
    var i = e.attr("action")
      , n = e.attr("method")
      , a = ($(t.target).find("#email").val(),
    {
        password: $("#password").val(),
        confirm_password: $("#confirm_password").val()
    });
    0 != $(".register_success_container").find(".alert").css("opacity") && $(".register_success_container").find(".alert").animate({
        opacity: 0
    }, 400, function() {
        $(".register_success_container").slideUp(300)
    }),
    0 != $(".login_alert_container").find(".alert").css("opacity") && $(".login_alert_container").find(".alert").animate({
        opacity: 0
    }, 400, function() {
        $(".login_alert_container").slideUp(300)
    }),
    $(t.currentTarget).find(".input-danger").removeClass("input-danger"),
    "" != $("#password").val() ? $.ajax({
        url: i,
        method: n,
        data: JSON.stringify(a),
        context: e,
        headers: {
            "Account-Name": getSubDomain(window.location.href)
        }
    }).done(function(e) {
        "OK" == e.status ? setTimeout(function() {
            var t = "<h2>Success!</h2>";
            $.each(e.message, function(e, i) {
                t += i + "<br/>"
            }),
            $(".register_success_container > .alert > strong").html(t),
            $(".login_alert_container").find(".alert").animate({
                opacity: 0
            }, 400, function() {
                $(".login_alert_container").slideUp(300, function() {
                    $(".register_success_container").slideDown(300, function() {
                        $(this).find(".alert").animate({
                            opacity: 1
                        }, 400)
                    })
                })
            }),
            $(".saveButton").removeAttr("disabled")
        }, 400) : setTimeout(function() {
            $.each(e.validation_results, function(e) {
                $(t.currentTarget).find('[name="' + e + '"]').each(function() {
                    $(this).addClass("input-danger")
                })
            });
            var i = e.message[0] + "<br/>";
            $(".login_alert_container > .alert > strong").html(i),
            $(".login_alert_container").slideDown(300, function() {
                $(this).find(".alert").animate({
                    opacity: 1
                }, 400)
            }),
            $(".saveButton").removeAttr("disabled")
        }, 400)
    }) : ($(".login_alert_container > .alert > strong").html("Please enter your new password."),
    $(".login_alert_container").slideDown(300, function() {
        $(this).find(".alert").animate({
            opacity: 1
        }, 400)
    }))
}
function exportToPDF(t) {
    t.preventDefault();
    var e = $(t.target);
    $.ajax({
        url: e.attr("href"),
        method: "PUT",
        context: t.target,
        headers: {
            Auth: getCookie("auth"),
            Session: getCookie("session_id"),
            "Account-Name": getCookie("account") ? getCookie("account") : domain.replace("."+main_domain,"")
        }
    }).done(function(t) {
		console.log(t);
        if ($(this).data("modalUrl")) {
            var e = $(this).data();
            t.download && (e.downloadFile = t.download),
            e.modalUrl = e.modalUrl,
            openModal(this, e)
        }
    })
}
function changeStatus(t) {
    t.preventDefault();
    var e = $(t.target);
    $.ajax({
        url: e.attr("href"),
        method: "PUT",
        context: t.target,
        headers: {
            Auth: getCookie("auth"),
            Session: getCookie("session_id"),
            "Account-Name": getCookie("account")
        }
    }).done(function(i) {
        if ("OK" == i.status) {
            var n = $(this).attr("href").replace(base_url, "").replace(api_base_url, "").split("/").slice(0)[0]
              , a = $(this).attr("href").split("/").slice(-1)[0]
              , r = $(this).attr("href").split("/").slice(-3)[0]
              , o = e.closest(".list_item_row");
            if ($(".status-preview-corner").length && ($(".status-preview-corner").removeClass(function(t, e) {
                return (e.match(/(^|\s)status-value-\S+/g) || []).join(" ")
            }),
            $(".status-preview-corner").addClass("status-value-" + a)),
            $.ajax({
                url: base_url + n + "/snippets/list_item/" + r,
                method: "GET",
                context: o,
                headers: {
                    Auth: getCookie("auth"),
                    Session: getCookie("session_id"),
                    "Account-Name": getCookie("account")
                }
            }).done(function(t) {
                $(this).replaceWith(t)
            }),
            $(this).data("modalUrl")) {
                var s = $(this).data();
                s.modalUrl = s.modalUrl,
                openModal(t = document, s)
            }
        } else {
            var s = {};
            s.modalUrl = base_url + "modal/error",
            s.modalHeading = "Error",
            s.modalBody = i.message,
            openModal(t = document, s)
        }
    })
}
function setReminderValues(t) {
    var e = $(t.target)
      , i = window.location.pathname.split("/")
      , n = e.attr("data-invoice-id")
      , a = $("#reminder_selection").val();
    -1 == i.indexOf("create") && "new" != n && $.ajax({
        url: api_base_url + "invoices/" + n + "/reminder/" + a,
        method: "PUT",
        context: t,
        headers: {
            Auth: getCookie("auth"),
            Session: getCookie("session_id"),
            "Account-Name": getCookie("account")
        }
    }).done(function() {}),
    $("#reminder_cta").length && (0 == a ? ($("#reminder").val(0),
    $("#reminder_cta").attr("href", base_url + "invoices/modal/reminder/" + a + "/" + n),
    $("#reminder_cta").html('<button type="button" class="btn btn-default">Add Payment Reminder <img style="padding-left:15px;" src="/images/reminder_icon.png" /></button>')) : ($("#reminder").val(a),
    $("#reminder_cta").attr("href", base_url + "invoices/modal/reminder/" + a + "/" + n),
    $("#reminder_cta").html("Reminder sent every " + a + " Days"))),
    $("[data-reminder-item-id='" + n + "']").length && $("[data-reminder-item-id='" + n + "']").attr("href", base_url + "invoices/modal/reminder/" + a + "/" + n)
}
function saveFormData(form) {
    $(form).find("saveFormData").attr("disabled", "disabled");
    var url = form.attr("action")
      , method = form.attr("method")
      , inputsObj = JSON.stringify(convertInputsToObject(form));
    if ("undefined" != typeof form.data("relatedSection"))
        var section = form.data("relatedSection");
    else {
        var section = url.replace(base_url, "").replace(api_base_url, "").split("/").slice(0)[0];
        if ("bulk" == section)
            var section = url.replace(base_url, "").replace(api_base_url, "").split("/").slice(1)[0]
    }
    $.ajax({
        url: url,
        method: method,
        data: inputsObj,
        headers: {
            Auth: getCookie("auth"),
            Session: getCookie("session_id"),
            "Account-Name": getCookie("account")
        }
    }).done(function(data) {
        if ($(form).find(".saveFormData").removeAttr("disabled"),
        $(form).find(".saveFormData").removeClass("loader"),
        "OK" == data.status) {
            if ("undefined" != typeof data.action && "undefined" != typeof data.action.account_name) {
                setCookie("account", data.action.account_name);
                var switchSubDomain = data.action.account_name
                  , redirProtocol = window.location.href.split(".")[0].split("/")[0]
                  , currentSubdomain = window.location.href.split(".")[0].split("/").slice(-1)[0]
                  , currentDomainPart = redirProtocol + "//" + currentSubdomain
                  , newDomainPart = redirProtocol + "//" + switchSubDomain;
                if (base_url = redirProtocol + "//" + switchSubDomain + "/",
                form.data("modalUrl")) {
                    var currentModalUrld = form.data("modalUrl");
                    form.data("modalUrl", currentModalUrld.replace(currentDomainPart, newDomainPart))
                }
            }
            if (form.data("redirectWithoutId"))
                var redirWithoutId = !0;
            else if ("undefined" != typeof form.data("relatedIds"))
                if (-1 != String(form.data("relatedIds")).indexOf(","))
                    var id = form.data("relatedIds").split(",")
                      , redirWithoutId = !0;
                else
                    var id = String(form.data("relatedIds"))
                      , redirWithoutId = !0;
            else if ("undefined" != typeof form.data("relatedId"))
                var id = form.data("relatedId");
            else if ("undefined" != typeof data.record_id)
                var id = data.record_id;
            else
                var id = url.split("/").slice(-1)[0];
            if (form.data("redirectUrl") && "" !== form.data("redirectUrl")) {
                if ("object" == typeof id || 1 == redirWithoutId)
                    var redirectURL = form.data("redirectUrl");
                else
                    var redirectURL = form.data("redirectUrl") + "/" + id;
                if ("undefined" != typeof switchSubDomain) {
                    form.data("modalUrl", encodeURI(form.data("modalUrl")));
                    var transferData = {
                        openModal: form.data()
                    };
                    window.location = redirectURL.replace(currentDomainPart, newDomainPart) + "?" + $.param(transferData)
                } else {
                    if (form.data("successFunction")) {
                        if ("undefined" == typeof ids && "number" == typeof id)
                            var ids = id;
                        eval(form.data("successFunction"))
                    }
                    easyAjax({
                        url: redirectURL,
                        targets: {
                            ".contentMainLeft": ".contentMainLeft",
                            ".sub_header_inner": ".sub_header_inner",
                            ".activityBar_inner": ".activityBar_inner"
                        },
                        headers: {
                            Auth: getCookie("auth"),
                            Session: getCookie("session_id"),
                            "Account-Name": getCookie("account")
                        },
                        preAjaxFunc: function() {
                            animateFullPageOut()
                        },
                        postAjaxFunc: function() {
                            animateFullPageIn()
                        }
                    })
                }
            } else if ("undefined" != typeof switchSubDomain) {
                form.data("modalUrl", encodeURI(form.data("modalUrl")));
                var transferData = {
                    openModal: form.data()
                }
                  , path = window.location.protocol + "//" + window.location.host + window.location.pathname;
                window.location = path.replace(currentDomainPart, newDomainPart) + "?" + $.param(transferData)
            } else
                updateListItems(form, id, section);
            "undefined" == typeof switchSubDomain && initFormResponseModal(form, id, data)
        } else if ($(".error").remove(),
        $(".input-danger").removeClass("input-danger"),
        "below" == form.data("validationPlacement"))
            if ($.each(data.validation_results, function(t, e) {
                form.find('[name="' + t + '"]').each(function() {
                    "none" == $(this).css("display") ? ($(this).parent().find(".bootstrap-select .btn").addClass("input-danger"),
                    $(this).parent().find(".bootstrap-select .btn").after('<div class="error">' + e + "</div>")) : ($(this).addClass("input-danger"),
                    $(this).after('<div class="error">' + e + "</div>"))
                })
            }),
            $(form).closest("#main_modal").length > 0) {
                var scrollDif = $(".input-danger").first().offset().top - 25 - $("#main_modal").offset().top
                  , currentModalScrol = $("#main_modal").scrollTop();
                $("#main_modal").animate({
                    scrollTop: currentModalScrol + scrollDif
                }, 500)
            } else
                $("html, body").animate({
                    scrollTop: $(".input-danger").first().offset().top - 15
                }, 500);
        else {
            $.each(data.validation_results, function(t, e) {
                "items" == t ? $.each(e, function(e, i) {
                    $.each(i, function(i) {
                        var n = t + "[" + e + "]['" + i + "']";
                        form.find('[name="' + n + '"]').each(function() {
                            "none" == $(this).css("display") ? $(this).parent().find(".bootstrap-select .btn").addClass("input-danger") : $(this).addClass("input-danger")
                        })
                    })
                }) : form.find('[name="' + t + '"]').each(function() {
                    "none" == $(this).css("display") ? $(this).parent().find(".bootstrap-select .btn").addClass("input-danger") : $(this).addClass("input-danger")
                })
            });
            var modalData = {};
            modalData.modalUrl = base_url + "modal/error",
            modalData.modalHeading = "Error",
            modalData.modalBody = data.message,
            openModal(e = document, modalData),
            $("html, body").animate({
                scrollTop: $(".input-danger").first().offset().top - 15
            }, 500)
        }
    })
}
function markEstInvoiced(t) {
    $.ajax({
        url: api_base_url + "estimates/" + t + "/status/invoiced",
        method: "PUT",
        headers: {
            Auth: getCookie("auth"),
            Session: getCookie("session_id"),
            "Account-Name": getCookie("account")
        }
    }).done(function(t) {
        if ("OK" != t.status) {
            var e = {};
            e.modalUrl = base_url + "modal/error",
            e.modalHeading = "Error",
            e.modalBody = 'An error has occurred changing the status of your estimate.<br> Please manually change the status to "invoiced".',
            openModal($(this), e)
        }
    })
}
function markDocStatus(t, e) {
    $.ajax({
        url: $(e).attr("href"),
        method: "PUT",
        context: e
    }).done(function(t) {
        if ("OK" == t.status) {
            var e = {};
            e.modalUrl = $(this).data("modalUrl"),
            e.modalHeading = $(this).data("modalHeading"),
            e.modalBody = $(this).data("modalBody"),
            openModal($(this), e)
        } else {
            var e = {};
            e.modalUrl = base_url + "modal/error",
            e.modalHeading = "Error",
            e.modalBody = "An error has occurred. Please try again later.",
            openModal($(this), e)
        }
    })
}
function filterStatement(t, e) {
    var i = {};
    if ($("#start_date").length && "none" != $("#start_date").val()) {
        var n = new Date($("#start_date").val());
        i.start_date = n.getFullYear() + "-" + (n.getMonth() + 1) + "-" + n.getDate()
    }
    var a = new Date($("#end_date").val());
    i.end_date = a.getFullYear() + "-" + (a.getMonth() + 1) + "-" + a.getDate(),
    easyAjax({
        url: e.data("redirectUrl"),
        targets: {
            ".contentMainLeft": ".contentMainLeft",
            ".sub_header_inner": ".sub_header_inner",
            ".activityBar_inner": ".activityBar_inner"
        },
        data: i,
        headers: {
            Auth: getCookie("auth"),
            Session: getCookie("session_id"),
            "Account-Name": getCookie("account")
        },
        preAjaxFunc: function() {
            animateFullPageOut()
        },
        postAjaxFunc: function() {
            animateFullPageIn(),
            $(".datepicker").datepicker({
				format: "yyyy-mm-dd",
                todayBtn: "linked"
            })
        }
    })
}
function initFormResponseModal(t, e, i) {
    if (t.data("modalUrl")) {
        var n = t.data();
        n.modalUrl = "object" == typeof e ? n.modalUrl : n.modalUrl + "/" + e,
        i.download && (n.downloadFile = i.download),
        openModal(t, n)
    }
}
function updateListItems(form, ids, section) {
    function updateItem(t) {
        if (rowElement = document.getElementById("bulk_action_CB[" + t + "]")) {
            var e = $(rowElement).closest(".list_item_row");
            $.ajax({
                url: base_url + section + "/snippets/list_item/" + t,
                method: "GET",
                context: e,
                headers: {
                    Auth: getCookie("auth"),
                    Session: getCookie("session_id"),
                    "Account-Name": getCookie("account")
                }
            }).done(function(t) {
                $(this).replaceWith(t)
            })
        }
    }
    if ("object" == typeof ids ? $.each(ids, function(t, e) {
        updateItem(e)
    }) : updateItem(ids),
    form.data("successFunction")) {
        if ("undefined" == typeof id && "number" == typeof ids)
            var id = ids;
        eval(form.data("successFunction"))
    }
}
function updateContactsList(t, e) {
    if ("undefined" == typeof e && (e = "contact_id"),
    "supplier_id" == e)
        var i = 2;
    else
        var i = 1;
    $('[name="' + e + '"]').each(function() {
        var e = t;
        $.ajax({
            url: base_url + "snippets/contacts_select/" + i,
            method: "GET",
            data: {
                current_id: e
            },
            context: this,
            headers: {
                Auth: getCookie("auth"),
                Session: getCookie("session_id"),
                "Account-Name": getCookie("account")
            }
        }).done(function(t) {
            $(this).parent().find(".bootstrap-select").remove(),
            $(this).replaceWith(t),
            $(".selectpicker").selectpicker()
        })
    })
}
function updateTaxList(t, e) {
    var i = /\[([^\]]*)\]/g;
    $("select.Tax").each(function() {
        var n = ($(this).attr("name"),
        $(this).find("option:selected").val());
        if (match = $(this).attr("name").match(i))
            var a = match[0].substring(1, match[0].length - 1);
        else
            var a = 0;
        a == t && (n = e),
        $.ajax({
            url: base_url + "snippets/taxes_select",
            method: "GET",
            context: this,
            data: {
                item_id: a,
                current_id: n
            },
            headers: {
                Auth: getCookie("auth"),
                Session: getCookie("session_id"),
                "Account-Name": getCookie("account")
            }
        }).done(function(t) {
            $(this).parent().find(".bootstrap-select").remove(),
            $(this).replaceWith(t),
            $(".selectpicker").selectpicker(),
            calculateInvoiceItemAmounts()
        })
    })
}
function updateSalesItem(t, e) {
    var i = "#" + t
      , n = convertInputsToObject(e);
    $(i).find(".Item").val(n.item_name),
    $(i).find(".Decsription").val(n.description),
    $(i).find(".Price").val(n.rate)
}
function activintyToggleInit() {
    if ("opened" == getCookie("activityToggle")) {
        if (.3 * $(window).width() > 320)
            var t = 320;
        else
            var t = "30%";
        $(".activity_toggle").addClass("opened"),
        $(".activityBar").width(t)
    } else
        $(".activityBar_inner").fadeOut(0),
        $(".activityBar").width(60)
}
function activintyToggle() {
    if ($(".activity_toggle").hasClass("opened"))
        $(".activityBar_inner").fadeOut(300, function() {
            $(".activityBar").animate({
                width: 60
            }, {
                duration: 400,
                complete: function() {
                    setCookie("activityToggle", "closed", 31536e6),
                    $(".activity_toggle").removeClass("opened")
                }
            })
        });
    else {
        if (.3 * $(window).width() > 320)
            var t = 320;
        else
            var t = "30%";
        $(".activityBar").animate({
            width: t
        }, {
            duration: 400,
            complete: function() {
                $(".activityBar_inner").fadeIn(300, function() {
                    setCookie("activityToggle", "opened", 31536e6),
                    $(".activity_toggle").addClass("opened")
                })
            }
        })
    }
}
function startSearch(t) {
    $.ajax({
        url: base_url + "search/all/" + t.value,
        method: "GET",
        context: t,
        headers: {
            Auth: getCookie("auth"),
            Session: getCookie("session_id"),
            "Account-Name": getCookie("account")
        }
    }).done(function(t) {
        $(this).parent().hasClass("open") || $("#search_overlay").dropdown("toggle"),
        $("#search_overlay").html(t),
        resizeSearchMaxHeight($("#search_overlay"))
    })
}
function searchRedirect(t, e) {
    t.preventDefault(),
    window.location = e
}
function startListItemSearch(t) {
    $(t).parent().find(".dropdown-menu").html('<li class="heading">Searching...</li>'),
    $(t).parent().hasClass("open") || $(t).dropdown("toggle"),
    clearTimeout(changeItemSearchTimer),
    changeItemSearchTimer = setTimeout(function() {
        $.ajax({
            url: base_url + "search/items/" + t.value,
            context: t,
            method: "GET",
            headers: {
                Auth: getCookie("auth"),
                Session: getCookie("session_id"),
                "Account-Name": getCookie("account")
            }
        }).done(function(t) {
            $(this).parent().hasClass("open") || $(this).dropdown("toggle"),
            $(this).parent().find(".dropdown-menu").html(t),
            resizeSearchMaxHeight($(".autoSearch"))
        })
    }, 400)
}
function resizeSearchMaxHeight(t) {
    var e = $(t).parent().find(".dropdown-menu");
    e.each(function() {
        var t = $(window).height()
          , e = $(this).offset().top - $(window).scrollTop();
        $(this).css("max-height", t - e - 20)
    })
}
function setInvoiceItem(t, e) {
    t.preventDefault(),
    $(e).closest(".itemRow").find(".Item").val($(e).attr("data-item")),
    $(e).closest(".itemRow").find(".Price").val($(e).attr("data-amount")),
    $(e).closest(".itemRow").find(".Description").val($(e).attr("data-description")),
    calculateInvoiceItemAmounts()
}
function removeInvoiceItem(t) {
    $(t).parent().parent().remove(),
    calculateInvoiceItemAmounts()
}
function calculateInvoiceItemAmounts() {
    var t = 0
      , e = 0
      , i = $("#discount_percentage").val();
    if (isNaN(i))
        var i = 0;
    var n = i / 100;
    $(".itemList > .row").not(".heaaderColTable").not(".listSubTotal").each(function() {
        var i = $(this).find(".Price").val()
          , a = $(this).find(".Qty").val()
          , r = $(this).find(".Tax").find(":selected").attr("data-tax-percentage") / 100;
        isNaN(i) && (i = 0),
        isNaN(a) && (a = 0),
        isNaN(r) && (r = 0);
        var o = a * i
          , s = o * n
          , l = o - s
          , c = l * r;
        $(this).find(".amountVal").text(commaSeperateNumber(roundNumber(o, 2))),
        t += o,
        e += c
    });
    var a = t * n;
    $(".listSubTotal #subTotal").text(commaSeperateNumber(roundNumber(t, 2))),
    $(".listSubTotal #subTotalDiscount").text(commaSeperateNumber(roundNumber(a, 2))),
    $(".listSubTotal #subTotalTax").text(commaSeperateNumber(roundNumber(e, 2)));
    var r = t - a + e;
    $(".listSubTotal #EndTotal").text(commaSeperateNumber(roundNumber(r, 2)))
}
function currencySelect(t) {
    if ($(t).attr("data-text") && $(t).attr("data-symbol"))
        var e = $(t).attr("data-text")
          , i = $(t).attr("data-symbol");
    else
        var e = $(t).find("[value='" + t.value + "']").attr("data-text")
          , i = $(t).find("[value='" + t.value + "']").attr("data-symbol");
    $(".currencyTextReplacement").text(e),
    $(".currencySymbolReplacement").text(i)
}
function roundNumber(t, e) {
    if (!e)
        return Math.round(t);
    if (0 == t) {
        for (var i = "", n = 0; e > n; n++)
            i += "0";
        return "0." + i
    }
    var a = Math.pow(10, e)
      , r = Math.round(t * a).toString();
    return r.slice(0, -1 * e) + "." + r.slice(-1 * e)
}
function commaSeperateNumber(t) {
    var e = t.replace(/[^0-9.]/g, "");
    if (-1 !== e.indexOf(".")) {
        var i = e.toString().split(".")
          , n = i.slice(0, -1).join("") + ".";
        n += i.slice(-1)[0].length > 2 ? i.slice(-1)[0].substr(0, 2) : i.slice(-1)
    } else
        var n = e;
    var a = n.toString()
      , r = a.indexOf(".");
    return a.replace(/\d(?=(?:\d{3})+(?:\.|$))/g, function(t, e) {
        return 0 > r || r > e ? t + "," : t
    })
}
function drag_start(t, e) {
    t.dataTransfer.setData("pos", ""),
    t.dataTransfer.setDragImage(e, 0, 0),
    $(e).parent().addClass("beingDragged")
}
function drop(t, e) {
    t.preventDefault(),
    $(e).after($(".beingDragged")),
    $(".beingDragged").removeClass("beingDragged"),
    $(e).stop().css("padding-bottom", "7px"),
    reOrderAfterDrag()
}
function reOrderAfterDrag() {
    var t = 0
      , e = /\[([^\]]*)\]/g;
    $(".dragRow").each(function() {
        $(this).find("input,select").each(function() {
            var i = $(this).attr("name").match(e);
            2 == i.length && $(this).attr("name", "items[" + t + "]" + i[1])
        }),
        t++
    })
}
function drag_over_test(t, e) {
    return t.preventDefault(),
    $(e).css("padding-bottom", "50px"),
    !1
}
function drag_out_test(t, e) {
    return t.preventDefault(),
    $(e).css("padding-bottom", "7px"),
    !1
}
function initTextAreTokenInsertion() {
    function t(t, e, i) {
        if (t.setSelectionRange)
            t.focus(),
            t.setSelectionRange(e, i);
        else if (t.createTextRange) {
            var n = t.createTextRange();
            n.collapse(!0),
            n.moveEnd("character", i),
            n.moveStart("character", e),
            n.select()
        }
    }
    var e, i = 0, n = 0;
    $(document).on("blur", ".tokenIntertArea", function(t) {
        t.preventDefault(),
        i = this.selectionStart,
        n = this.selectionEnd,
        e = $(this)
    }),
    $(document).on("focus", ".tokenIntertArea", function() {
        $(".tokenSelector").removeAttr("disabled"),
        $(".tokenSelector").selectpicker("val", "default"),
        $(".tokenSelector").selectpicker("refresh")
    }),
    $(document).on("click", ".tokenSelection", function(a) {
        a.preventDefault();
        var r = $(this).attr("data-tokens");
        "undefined" != typeof e && ($.trim(e.val()) ? (e.val(e.val().substring(0, i) + r + e.val().substring(n, e.val().length)),
        t(e[0], i + r.length, i + r.length)) : (e.val(r),
        e.focus()))
    })
}
function setCookie(t, e, i) {
    if ("undefined" != typeof i && 0 !== i) {
        if (isNaN(i))
            var n = new Date(i.replace(/-/g, "/"));
        else {
            var n = new Date;
            n.setTime(n.getTime() + i)
        }
        var a = " expires=" + n.toUTCString() + ";"
    } else
        var a = "";
    document.cookie = t + "=" + e + "; " + a + " path=/; domain=" + main_domain + "; "
}
function getCookie(t) {
    for (var e = t + "=", i = document.cookie.split(";"), n = 0; n < i.length; n++) {
        for (var a = i[n]; " " == a.charAt(0); )
            a = a.substring(1);
        if (0 == a.indexOf(e))
            return a.substring(e.length, a.length)
    }
    return ""
}
function initCustomScrollers() {
    $(".activityBar_outer").mCustomScrollbar({
        setWidth: !1,
        setHeight: !1,
        setTop: 0,
        setLeft: 0,
        axis: "y",
        scrollbarPosition: "inside",
        scrollInertia: 950,
        autoDraggerLength: !0,
        autoHideScrollbar: !1,
        autoExpandScrollbar: !1,
        alwaysShowScrollbar: 0,
        snapAmount: null,
        snapOffset: 0,
        mouseWheel: {
            enable: !0,
            scrollAmount: "auto",
            axis: "y",
            preventDefault: !1,
            deltaFactor: "auto",
            normalizeDelta: !1,
            invert: !1,
            disableOver: ["select", "option", "keygen", "datalist", "textarea"]
        },
        scrollButtons: {
            enable: !1,
            scrollType: "stepless",
            scrollAmount: "auto"
        },
        keyboard: {
            enable: !0,
            scrollType: "stepless",
            scrollAmount: "auto"
        },
        contentTouchScroll: 25,
        advanced: {
            autoExpandHorizontalScroll: !1,
            autoScrollOnFocus: "input,textarea,select,button,datalist,keygen,a[tabindex],area,object,[contenteditable='true']",
            updateOnContentResize: !0,
            updateOnImageLoad: !0,
            updateOnSelectorChange: !1,
            releaseDraggableSelectors: !1
        },
        theme: "light",
        callbacks: {
            onInit: !1,
            onScrollStart: !1,
            onScroll: !1,
            onTotalScroll: function() {
                updateActivityBar()
            },
            onTotalScrollBack: !1,
            whileScrolling: !1,
            onTotalScrollOffset: 0,
            onTotalScrollBackOffset: 0,
            alwaysTriggerOffsets: !0,
            onOverflowY: !1,
            onOverflowX: !1,
            onOverflowYNone: !1,
            onOverflowXNone: !1
        },
        live: !1,
        liveSelector: null
    })
}
function convertInputsToObject(t) {
    var e = {}
      , n = t.serializeArray();
    for (i = 0; i < n.length; i++) {
        var a = /\[([^\]]*)\]/g;
        if (match = n[i].name.match(a)) {
            var r = n[i].name.substr(0, n[i].name.indexOf("["))
              , o = String(match[0].substring(1, match[0].length - 1));
            if (1 == match.length)
                "object" != typeof e[r] && (e[r] = {}),
                e[r][o] = n[i].value;
            else {
                var s = match[1].substring(2, match[1].length - 2);
                isNaN(o.replace(/'/g, "")) || (o = o.replace(/'/g, "")),
                isNaN(s.replace(/'/g, "")) || (s = s.replace(/'/g, "")),
                "object" != typeof e[r] && (e[r] = {}),
                "object" != typeof e[r][o] && (e[r][o] = {}),
                e[r][o][s] = n[i].value
            }
        } else
            e[n[i].name] = n[i].value
    }
    return e
}
function showBulkModal(t) {
    t.preventDefault();
    var e = $(t.target)
      , i = Array()
      , n = /\[([^\]]*)\]/g;
    if ($(".bulkItemRow_cb:checkbox:checked").length) {
        $(".bulkItemRow_cb:checkbox:checked").each(function() {
            $(this).attr("id");
            (regMatch = $(this).attr("id").match(n)) && i.push(regMatch[0].substr(1, regMatch[0].length - 2))
        });
        var a = {};
        a.modalUrl = e.val(),
        a.applicableIds = i,
        openModal(t = document, a),
        e.val("Bulk Actions"),
        e.selectpicker("render")
    } else {
        var a = {};
        a.modalUrl = base_url + "modal/error",
        a.modalHeading = "Error",
        a.modalBody = "Please select which items you would like to appy the bulk action to.",
        openModal(t = document, a),
        e.val("Bulk Actions"),
        e.selectpicker("render")
    }
}
function archive(t) {
    t.preventDefault();
    var e = {}
      , i = $(t.target).attr("href")
      , n = i.split("/")
      , a = n.slice(-1)[0];
    n.pop();
    var r = n.join("/");
    e.modalUrl = r,
    e.applicableIds = a,
    openModal(t = document, e)
}
function openSendStatement() {}
function showReciept(t) {
    $("#image_modal .modal-body").html('<img class="modal_preview_image" src="' + t + '" />'),
    $("#image_modal").modal("show")
}
function changeAccountName(t) {
    var e = $(t.target).val()
      , i = e.replace(/[^A-Za-z0-9]/g, "").toLowerCase();
    $(t.target).val(i),
    $("#acc_url_preview").text(i),
    i != getCookie("account") ? ($("#acc_url_preview_status").css("color", "#e38d13"),
    $("#acc_url_preview_status").html("Checking <b>...</b>"),
    clearTimeout(changeAccountTimer),
    changeAccountTimer = setTimeout(function() {
        $.ajax({
            url: api_base_url + "checks/account_exists/" + i,
            method: "PUT",
            headers: {
                Auth: getCookie("auth"),
                Session: getCookie("session_id"),
                "Account-Name": getCookie("account")
            }
        }).done(function(t) {
            $("#acc_url_preview").text() == i && ("ERROR" == t.status ? ($("#acc_url_preview_status").css("color", "#87c94f"),
            $("#acc_url_preview_status").html("Available &#10004;")) : ($("#acc_url_preview_status").css("color", "#d9534f"),
            $("#acc_url_preview_status").html("Not Available &#10008;")),
            $("#acc_url_preview").text(i))
        })
    }, 800)) : ($("#acc_url_preview_status").css("color", "#87c94f"),
    $("#acc_url_preview_status").html("&#10004;"))
}
function insertActivityBarData() {
    if ($("#activity_data").length) {
        var t = base_url + "snippets/activities";
        $("#activity_bar_content").attr("data-activity-category") && (t += "/" + $("#activity_bar_content").attr("data-activity-category"),
        $("#activity_bar_content").attr("data-activity-document-id") && (t += "/" + $("#activity_bar_content").attr("data-activity-document-id"))),
        $("#activity_data").fadeOut(400, function() {
            $("#activity_loader > img").animate({
                opacity: 1
            }, 400),
            $.ajax({
                url: t,
                method: "GET",
                data: {
                    limit: 20
                },
                headers: {
                    Auth: getCookie("auth"),
                    Session: getCookie("session_id"),
                    "Account-Name": getCookie("account")
                }
            }).done(function(t) {
                $("#activity_loader > img").stop(!0).animate({
                    opacity: 0
                }, 400, function() {
                    $("#activity_data").html(t),
                    $("#activity_data").fadeIn(400)
                })
            })
        })
    }
}
function updateActivityBar() {
    "true" != $("#activity_bar_content").attr("data-activity-end-reached") && $("#activity_loader > img").animate({
        opacity: 1
    }, function() {
        var t = base_url + "snippets/activities"
          , e = $("#activity_data > .activity_item").length;
        $("#activity_bar_content").attr("data-activity-category") && (t += "/" + $("#activity_bar_content").attr("data-activity-category"),
        $("#activity_bar_content").attr("data-activity-document-id") && (t += "/" + $("#activity_bar_content").attr("data-activity-document-id"))),
        $.ajax({
            url: t,
            method: "GET",
            data: {
                offset: e
            },
            headers: {
                Auth: getCookie("auth"),
                Session: getCookie("session_id"),
                "Account-Name": getCookie("account")
            }
        }).done(function(t) {
            "" != t ? $("#activity_loader > img").stop(!0).animate({
                opacity: 0
            }, 400, function() {
                $("#activity_data").append(t),
                $("#activity_loader > img").animate({
                    opacity: 0
                }, 400)
            }) : $("#activity_loader > img").stop(!0).animate({
                opacity: 0
            }, 400, function() {
                $("#activity_bar_content").attr("data-activity-end-reached", "true"),
                $("#activity_data").append('<div class="activity_item">End of Activity</div>'),
                $("#activity_loader > img").animate({
                    opacity: 0
                }, 400)
            })
        })
    })
}
function viewMapping(t, e, i) {
    $.ajax({
        url: t,
        method: e,
        data: i,
        headers: {
            Auth: getCookie("auth"),
            Session: getCookie("session_id"),
            "Account-Name": getCookie("account")
        }
    }).done(function(t) {
        console.log(t)
    })
}
function setClickedHref(url){
	ajaxUrl = url;
}
function animateFullPageOut() {
    $(".main_content_loader").fadeIn(300),
    $(".sub_header_inner").animate({
        opacity: 0
    }, 500),
    $(".contentMainLeft").animate({
        opacity: 0
    }, 500),
    $(".activityBar_inner").animate({
        opacity: 0
    }, 500)
}
function animateFullPageIn() {
    $(".main_content_loader").fadeOut(300, function() {
        $(".selectpicker").selectpicker(),
        $(".sub_header_inner").animate({
            opacity: 1
        }, 500),
        $(".contentMainLeft").delay(300).animate({
            opacity: 1
        }, 500),
        $(".activityBar_inner").delay(600).animate({
            opacity: 1
        }, 500),
        $("html, body").animate({
            scrollTop: 0
        }, 500, function() {
            insertActivityBarData()
        })
    })
}
!function(t, e) {
    "object" == typeof module && "object" == typeof module.exports ? module.exports = t.document ? e(t, !0) : function(t) {
        if (!t.document)
            throw new Error("jQuery requires a window with a document");
        return e(t)
    }
    : e(t)
}("undefined" != typeof window ? window : this, function(t, e) {
    function i(t) {
        var e = "length"in t && t.length
          , i = ae.type(t);
        return "function" === i || ae.isWindow(t) ? !1 : 1 === t.nodeType && e ? !0 : "array" === i || 0 === e || "number" == typeof e && e > 0 && e - 1 in t
    }
    function n(t, e, i) {
        if (ae.isFunction(e))
            return ae.grep(t, function(t, n) {
                return !!e.call(t, n, t) !== i
            });
        if (e.nodeType)
            return ae.grep(t, function(t) {
                return t === e !== i
            });
        if ("string" == typeof e) {
            if (he.test(e))
                return ae.filter(e, t, i);
            e = ae.filter(e, t)
        }
        return ae.grep(t, function(t) {
            return ae.inArray(t, e) >= 0 !== i
        })
    }
    function a(t, e) {
        do
            t = t[e];
        while (t && 1 !== t.nodeType);return t
    }
    function r(t) {
        var e = be[t] = {};
        return ae.each(t.match(ye) || [], function(t, i) {
            e[i] = !0
        }),
        e
    }
    function o() {
        fe.addEventListener ? (fe.removeEventListener("DOMContentLoaded", s, !1),
        t.removeEventListener("load", s, !1)) : (fe.detachEvent("onreadystatechange", s),
        t.detachEvent("onload", s))
    }
    function s() {
        (fe.addEventListener || "load" === event.type || "complete" === fe.readyState) && (o(),
        ae.ready())
    }
    function l(t, e, i) {
        if (void 0 === i && 1 === t.nodeType) {
            var n = "data-" + e.replace(Se, "-$1").toLowerCase();
            if (i = t.getAttribute(n),
            "string" == typeof i) {
                try {
                    i = "true" === i ? !0 : "false" === i ? !1 : "null" === i ? null : +i + "" === i ? +i : Ce.test(i) ? ae.parseJSON(i) : i
                } catch (a) {}
                ae.data(t, e, i)
            } else
                i = void 0
        }
        return i
    }
    function c(t) {
        var e;
        for (e in t)
            if (("data" !== e || !ae.isEmptyObject(t[e])) && "toJSON" !== e)
                return !1;
        return !0
    }
    function u(t, e, i, n) {
        if (ae.acceptData(t)) {
            var a, r, o = ae.expando, s = t.nodeType, l = s ? ae.cache : t, c = s ? t[o] : t[o] && o;
            if (c && l[c] && (n || l[c].data) || void 0 !== i || "string" != typeof e)
                return c || (c = s ? t[o] = V.pop() || ae.guid++ : o),
                l[c] || (l[c] = s ? {} : {
                    toJSON: ae.noop
                }),
                ("object" == typeof e || "function" == typeof e) && (n ? l[c] = ae.extend(l[c], e) : l[c].data = ae.extend(l[c].data, e)),
                r = l[c],
                n || (r.data || (r.data = {}),
                r = r.data),
                void 0 !== i && (r[ae.camelCase(e)] = i),
                "string" == typeof e ? (a = r[e],
                null == a && (a = r[ae.camelCase(e)])) : a = r,
                a
        }
    }
    function d(t, e, i) {
        if (ae.acceptData(t)) {
            var n, a, r = t.nodeType, o = r ? ae.cache : t, s = r ? t[ae.expando] : ae.expando;
            if (o[s]) {
                if (e && (n = i ? o[s] : o[s].data)) {
                    ae.isArray(e) ? e = e.concat(ae.map(e, ae.camelCase)) : e in n ? e = [e] : (e = ae.camelCase(e),
                    e = e in n ? [e] : e.split(" ")),
                    a = e.length;
                    for (; a--; )
                        delete n[e[a]];
                    if (i ? !c(n) : !ae.isEmptyObject(n))
                        return
                }
                (i || (delete o[s].data,
                c(o[s]))) && (r ? ae.cleanData([t], !0) : ie.deleteExpando || o != o.window ? delete o[s] : o[s] = null)
            }
        }
    }
    function h() {
        return !0
    }
    function p() {
        return !1
    }
    function f() {
        try {
            return fe.activeElement
        } catch (t) {}
    }
    function m(t) {
        var e = Ie.split("|")
          , i = t.createDocumentFragment();
        if (i.createElement)
            for (; e.length; )
                i.createElement(e.pop());
        return i
    }
    function g(t, e) {
        var i, n, a = 0, r = typeof t.getElementsByTagName !== Te ? t.getElementsByTagName(e || "*") : typeof t.querySelectorAll !== Te ? t.querySelectorAll(e || "*") : void 0;
        if (!r)
            for (r = [],
            i = t.childNodes || t; null != (n = i[a]); a++)
                !e || ae.nodeName(n, e) ? r.push(n) : ae.merge(r, g(n, e));
        return void 0 === e || e && ae.nodeName(t, e) ? ae.merge([t], r) : r
    }
    function v(t) {
        Oe.test(t.type) && (t.defaultChecked = t.checked)
    }
    function _(t, e) {
        return ae.nodeName(t, "table") && ae.nodeName(11 !== e.nodeType ? e : e.firstChild, "tr") ? t.getElementsByTagName("tbody")[0] || t.appendChild(t.ownerDocument.createElement("tbody")) : t
    }
    function y(t) {
        return t.type = (null !== ae.find.attr(t, "type")) + "/" + t.type,
        t
    }
    function b(t) {
        var e = Xe.exec(t.type);
        return e ? t.type = e[1] : t.removeAttribute("type"),
        t
    }
    function x(t, e) {
        for (var i, n = 0; null != (i = t[n]); n++)
            ae._data(i, "globalEval", !e || ae._data(e[n], "globalEval"))
    }
    function w(t, e) {
        if (1 === e.nodeType && ae.hasData(t)) {
            var i, n, a, r = ae._data(t), o = ae._data(e, r), s = r.events;
            if (s) {
                delete o.handle,
                o.events = {};
                for (i in s)
                    for (n = 0,
                    a = s[i].length; a > n; n++)
                        ae.event.add(e, i, s[i][n])
            }
            o.data && (o.data = ae.extend({}, o.data))
        }
    }
    function T(t, e) {
        var i, n, a;
        if (1 === e.nodeType) {
            if (i = e.nodeName.toLowerCase(),
            !ie.noCloneEvent && e[ae.expando]) {
                a = ae._data(e);
                for (n in a.events)
                    ae.removeEvent(e, n, a.handle);
                e.removeAttribute(ae.expando)
            }
            "script" === i && e.text !== t.text ? (y(e).text = t.text,
            b(e)) : "object" === i ? (e.parentNode && (e.outerHTML = t.outerHTML),
            ie.html5Clone && t.innerHTML && !ae.trim(e.innerHTML) && (e.innerHTML = t.innerHTML)) : "input" === i && Oe.test(t.type) ? (e.defaultChecked = e.checked = t.checked,
            e.value !== t.value && (e.value = t.value)) : "option" === i ? e.defaultSelected = e.selected = t.defaultSelected : ("input" === i || "textarea" === i) && (e.defaultValue = t.defaultValue)
        }
    }
    function C(e, i) {
        var n, a = ae(i.createElement(e)).appendTo(i.body), r = t.getDefaultComputedStyle && (n = t.getDefaultComputedStyle(a[0])) ? n.display : ae.css(a[0], "display");
        return a.detach(),
        r
    }
    function S(t) {
        var e = fe
          , i = Ze[t];
        return i || (i = C(t, e),
        "none" !== i && i || (Ke = (Ke || ae("<iframe frameborder='0' width='0' height='0'/>")).appendTo(e.documentElement),
        e = (Ke[0].contentWindow || Ke[0].contentDocument).document,
        e.write(),
        e.close(),
        i = C(t, e),
        Ke.detach()),
        Ze[t] = i),
        i
    }
    function k(t, e) {
        return {
            get: function() {
                var i = t();
                return null != i ? i ? void delete this.get : (this.get = e).apply(this, arguments) : void 0
            }
        }
    }
    function $(t, e) {
        if (e in t)
            return e;
        for (var i = e.charAt(0).toUpperCase() + e.slice(1), n = e, a = hi.length; a--; )
            if (e = hi[a] + i,
            e in t)
                return e;
        return n
    }
    function D(t, e) {
        for (var i, n, a, r = [], o = 0, s = t.length; s > o; o++)
            n = t[o],
            n.style && (r[o] = ae._data(n, "olddisplay"),
            i = n.style.display,
            e ? (r[o] || "none" !== i || (n.style.display = ""),
            "" === n.style.display && De(n) && (r[o] = ae._data(n, "olddisplay", S(n.nodeName)))) : (a = De(n),
            (i && "none" !== i || !a) && ae._data(n, "olddisplay", a ? i : ae.css(n, "display"))));
        for (o = 0; s > o; o++)
            n = t[o],
            n.style && (e && "none" !== n.style.display && "" !== n.style.display || (n.style.display = e ? r[o] || "" : "none"));
        return t
    }
    function A(t, e, i) {
        var n = li.exec(e);
        return n ? Math.max(0, n[1] - (i || 0)) + (n[2] || "px") : e
    }
    function O(t, e, i, n, a) {
        for (var r = i === (n ? "border" : "content") ? 4 : "width" === e ? 1 : 0, o = 0; 4 > r; r += 2)
            "margin" === i && (o += ae.css(t, i + $e[r], !0, a)),
            n ? ("content" === i && (o -= ae.css(t, "padding" + $e[r], !0, a)),
            "margin" !== i && (o -= ae.css(t, "border" + $e[r] + "Width", !0, a))) : (o += ae.css(t, "padding" + $e[r], !0, a),
            "padding" !== i && (o += ae.css(t, "border" + $e[r] + "Width", !0, a)));
        return o
    }
    function E(t, e, i) {
        var n = !0
          , a = "width" === e ? t.offsetWidth : t.offsetHeight
          , r = ti(t)
          , o = ie.boxSizing && "border-box" === ae.css(t, "boxSizing", !1, r);
        if (0 >= a || null == a) {
            if (a = ei(t, e, r),
            (0 > a || null == a) && (a = t.style[e]),
            ni.test(a))
                return a;
            n = o && (ie.boxSizingReliable() || a === t.style[e]),
            a = parseFloat(a) || 0
        }
        return a + O(t, e, i || (o ? "border" : "content"), n, r) + "px"
    }
    function P(t, e, i, n, a) {
        return new P.prototype.init(t,e,i,n,a)
    }
    function N() {
        return setTimeout(function() {
            pi = void 0
        }),
        pi = ae.now()
    }
    function M(t, e) {
        var i, n = {
            height: t
        }, a = 0;
        for (e = e ? 1 : 0; 4 > a; a += 2 - e)
            i = $e[a],
            n["margin" + i] = n["padding" + i] = t;
        return e && (n.opacity = n.width = t),
        n
    }
    function R(t, e, i) {
        for (var n, a = (yi[e] || []).concat(yi["*"]), r = 0, o = a.length; o > r; r++)
            if (n = a[r].call(i, e, t))
                return n
    }
    function I(t, e, i) {
        var n, a, r, o, s, l, c, u, d = this, h = {}, p = t.style, f = t.nodeType && De(t), m = ae._data(t, "fxshow");
        i.queue || (s = ae._queueHooks(t, "fx"),
        null == s.unqueued && (s.unqueued = 0,
        l = s.empty.fire,
        s.empty.fire = function() {
            s.unqueued || l()
        }
        ),
        s.unqueued++,
        d.always(function() {
            d.always(function() {
                s.unqueued--,
                ae.queue(t, "fx").length || s.empty.fire()
            })
        })),
        1 === t.nodeType && ("height"in e || "width"in e) && (i.overflow = [p.overflow, p.overflowX, p.overflowY],
        c = ae.css(t, "display"),
        u = "none" === c ? ae._data(t, "olddisplay") || S(t.nodeName) : c,
        "inline" === u && "none" === ae.css(t, "float") && (ie.inlineBlockNeedsLayout && "inline" !== S(t.nodeName) ? p.zoom = 1 : p.display = "inline-block")),
        i.overflow && (p.overflow = "hidden",
        ie.shrinkWrapBlocks() || d.always(function() {
            p.overflow = i.overflow[0],
            p.overflowX = i.overflow[1],
            p.overflowY = i.overflow[2]
        }));
        for (n in e)
            if (a = e[n],
            mi.exec(a)) {
                if (delete e[n],
                r = r || "toggle" === a,
                a === (f ? "hide" : "show")) {
                    if ("show" !== a || !m || void 0 === m[n])
                        continue;
                    f = !0
                }
                h[n] = m && m[n] || ae.style(t, n)
            } else
                c = void 0;
        if (ae.isEmptyObject(h))
            "inline" === ("none" === c ? S(t.nodeName) : c) && (p.display = c);
        else {
            m ? "hidden"in m && (f = m.hidden) : m = ae._data(t, "fxshow", {}),
            r && (m.hidden = !f),
            f ? ae(t).show() : d.done(function() {
                ae(t).hide()
            }),
            d.done(function() {
                var e;
                ae._removeData(t, "fxshow");
                for (e in h)
                    ae.style(t, e, h[e])
            });
            for (n in h)
                o = R(f ? m[n] : 0, n, d),
                n in m || (m[n] = o.start,
                f && (o.end = o.start,
                o.start = "width" === n || "height" === n ? 1 : 0))
        }
    }
    function B(t, e) {
        var i, n, a, r, o;
        for (i in t)
            if (n = ae.camelCase(i),
            a = e[n],
            r = t[i],
            ae.isArray(r) && (a = r[1],
            r = t[i] = r[0]),
            i !== n && (t[n] = r,
            delete t[i]),
            o = ae.cssHooks[n],
            o && "expand"in o) {
                r = o.expand(r),
                delete t[n];
                for (i in r)
                    i in t || (t[i] = r[i],
                    e[i] = a)
            } else
                e[n] = a
    }
    function L(t, e, i) {
        var n, a, r = 0, o = _i.length, s = ae.Deferred().always(function() {
            delete l.elem
        }), l = function() {
            if (a)
                return !1;
            for (var e = pi || N(), i = Math.max(0, c.startTime + c.duration - e), n = i / c.duration || 0, r = 1 - n, o = 0, l = c.tweens.length; l > o; o++)
                c.tweens[o].run(r);
            return s.notifyWith(t, [c, r, i]),
            1 > r && l ? i : (s.resolveWith(t, [c]),
            !1)
        }, c = s.promise({
            elem: t,
            props: ae.extend({}, e),
            opts: ae.extend(!0, {
                specialEasing: {}
            }, i),
            originalProperties: e,
            originalOptions: i,
            startTime: pi || N(),
            duration: i.duration,
            tweens: [],
            createTween: function(e, i) {
                var n = ae.Tween(t, c.opts, e, i, c.opts.specialEasing[e] || c.opts.easing);
                return c.tweens.push(n),
                n
            },
            stop: function(e) {
                var i = 0
                  , n = e ? c.tweens.length : 0;
                if (a)
                    return this;
                for (a = !0; n > i; i++)
                    c.tweens[i].run(1);
                return e ? s.resolveWith(t, [c, e]) : s.rejectWith(t, [c, e]),
                this
            }
        }), u = c.props;
        for (B(u, c.opts.specialEasing); o > r; r++)
            if (n = _i[r].call(c, t, u, c.opts))
                return n;
        return ae.map(u, R, c),
        ae.isFunction(c.opts.start) && c.opts.start.call(t, c),
        ae.fx.timer(ae.extend(l, {
            elem: t,
            anim: c,
            queue: c.opts.queue
        })),
        c.progress(c.opts.progress).done(c.opts.done, c.opts.complete).fail(c.opts.fail).always(c.opts.always)
    }
    function j(t) {
        return function(e, i) {
            "string" != typeof e && (i = e,
            e = "*");
            var n, a = 0, r = e.toLowerCase().match(ye) || [];
            if (ae.isFunction(i))
                for (; n = r[a++]; )
                    "+" === n.charAt(0) ? (n = n.slice(1) || "*",
                    (t[n] = t[n] || []).unshift(i)) : (t[n] = t[n] || []).push(i)
        }
    }
    function F(t, e, i, n) {
        function a(s) {
            var l;
            return r[s] = !0,
            ae.each(t[s] || [], function(t, s) {
                var c = s(e, i, n);
                return "string" != typeof c || o || r[c] ? o ? !(l = c) : void 0 : (e.dataTypes.unshift(c),
                a(c),
                !1)
            }),
            l
        }
        var r = {}
          , o = t === Hi;
        return a(e.dataTypes[0]) || !r["*"] && a("*")
    }
    function U(t, e) {
        var i, n, a = ae.ajaxSettings.flatOptions || {};
        for (n in e)
            void 0 !== e[n] && ((a[n] ? t : i || (i = {}))[n] = e[n]);
        return i && ae.extend(!0, t, i),
        t
    }
    function z(t, e, i) {
        for (var n, a, r, o, s = t.contents, l = t.dataTypes; "*" === l[0]; )
            l.shift(),
            void 0 === a && (a = t.mimeType || e.getResponseHeader("Content-Type"));
        if (a)
            for (o in s)
                if (s[o] && s[o].test(a)) {
                    l.unshift(o);
                    break
                }
        if (l[0]in i)
            r = l[0];
        else {
            for (o in i) {
                if (!l[0] || t.converters[o + " " + l[0]]) {
                    r = o;
                    break
                }
                n || (n = o)
            }
            r = r || n
        }
        return r ? (r !== l[0] && l.unshift(r),
        i[r]) : void 0
    }
    function H(t, e, i, n) {
        var a, r, o, s, l, c = {}, u = t.dataTypes.slice();
        if (u[1])
            for (o in t.converters)
                c[o.toLowerCase()] = t.converters[o];
        for (r = u.shift(); r; )
            if (t.responseFields[r] && (i[t.responseFields[r]] = e),
            !l && n && t.dataFilter && (e = t.dataFilter(e, t.dataType)),
            l = r,
            r = u.shift())
                if ("*" === r)
                    r = l;
                else if ("*" !== l && l !== r) {
                    if (o = c[l + " " + r] || c["* " + r],
                    !o)
                        for (a in c)
                            if (s = a.split(" "),
                            s[1] === r && (o = c[l + " " + s[0]] || c["* " + s[0]])) {
                                o === !0 ? o = c[a] : c[a] !== !0 && (r = s[0],
                                u.unshift(s[1]));
                                break
                            }
                    if (o !== !0)
                        if (o && t["throws"])
                            e = o(e);
                        else
                            try {
                                e = o(e)
                            } catch (d) {
                                return {
                                    state: "parsererror",
                                    error: o ? d : "No conversion from " + l + " to " + r
                                }
                            }
                }
        return {
            state: "success",
            data: e
        }
    }
    function W(t, e, i, n) {
        var a;
        if (ae.isArray(e))
            ae.each(e, function(e, a) {
                i || Xi.test(t) ? n(t, a) : W(t + "[" + ("object" == typeof a ? e : "") + "]", a, i, n)
            });
        else if (i || "object" !== ae.type(e))
            n(t, e);
        else
            for (a in e)
                W(t + "[" + a + "]", e[a], i, n)
    }
    function q() {
        try {
            return new t.XMLHttpRequest
        } catch (e) {}
    }
    function Y() {
        try {
            return new t.ActiveXObject("Microsoft.XMLHTTP")
        } catch (e) {}
    }
    function X(t) {
        return ae.isWindow(t) ? t : 9 === t.nodeType ? t.defaultView || t.parentWindow : !1
    }
    var V = []
      , G = V.slice
      , Q = V.concat
      , J = V.push
      , K = V.indexOf
      , Z = {}
      , te = Z.toString
      , ee = Z.hasOwnProperty
      , ie = {}
      , ne = "1.11.3"
      , ae = function(t, e) {
        return new ae.fn.init(t,e)
    }
      , re = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g
      , oe = /^-ms-/
      , se = /-([\da-z])/gi
      , le = function(t, e) {
        return e.toUpperCase()
    };
    ae.fn = ae.prototype = {
        jquery: ne,
        constructor: ae,
        selector: "",
        length: 0,
        toArray: function() {
            return G.call(this)
        },
        get: function(t) {
            return null != t ? 0 > t ? this[t + this.length] : this[t] : G.call(this)
        },
        pushStack: function(t) {
            var e = ae.merge(this.constructor(), t);
            return e.prevObject = this,
            e.context = this.context,
            e
        },
        each: function(t, e) {
            return ae.each(this, t, e)
        },
        map: function(t) {
            return this.pushStack(ae.map(this, function(e, i) {
                return t.call(e, i, e)
            }))
        },
        slice: function() {
            return this.pushStack(G.apply(this, arguments))
        },
        first: function() {
            return this.eq(0)
        },
        last: function() {
            return this.eq(-1)
        },
        eq: function(t) {
            var e = this.length
              , i = +t + (0 > t ? e : 0);
            return this.pushStack(i >= 0 && e > i ? [this[i]] : [])
        },
        end: function() {
            return this.prevObject || this.constructor(null)
        },
        push: J,
        sort: V.sort,
        splice: V.splice
    },
    ae.extend = ae.fn.extend = function() {
        var t, e, i, n, a, r, o = arguments[0] || {}, s = 1, l = arguments.length, c = !1;
        for ("boolean" == typeof o && (c = o,
        o = arguments[s] || {},
        s++),
        "object" == typeof o || ae.isFunction(o) || (o = {}),
        s === l && (o = this,
        s--); l > s; s++)
            if (null != (a = arguments[s]))
                for (n in a)
                    t = o[n],
                    i = a[n],
                    o !== i && (c && i && (ae.isPlainObject(i) || (e = ae.isArray(i))) ? (e ? (e = !1,
                    r = t && ae.isArray(t) ? t : []) : r = t && ae.isPlainObject(t) ? t : {},
                    o[n] = ae.extend(c, r, i)) : void 0 !== i && (o[n] = i));
        return o
    }
    ,
    ae.extend({
        expando: "jQuery" + (ne + Math.random()).replace(/\D/g, ""),
        isReady: !0,
        error: function(t) {
            throw new Error(t)
        },
        noop: function() {},
        isFunction: function(t) {
            return "function" === ae.type(t)
        },
        isArray: Array.isArray || function(t) {
            return "array" === ae.type(t)
        }
        ,
        isWindow: function(t) {
            return null != t && t == t.window
        },
        isNumeric: function(t) {
            return !ae.isArray(t) && t - parseFloat(t) + 1 >= 0
        },
        isEmptyObject: function(t) {
            var e;
            for (e in t)
                return !1;
            return !0
        },
        isPlainObject: function(t) {
            var e;
            if (!t || "object" !== ae.type(t) || t.nodeType || ae.isWindow(t))
                return !1;
            try {
                if (t.constructor && !ee.call(t, "constructor") && !ee.call(t.constructor.prototype, "isPrototypeOf"))
                    return !1
            } catch (i) {
                return !1
            }
            if (ie.ownLast)
                for (e in t)
                    return ee.call(t, e);
            for (e in t)
                ;
            return void 0 === e || ee.call(t, e)
        },
        type: function(t) {
            return null == t ? t + "" : "object" == typeof t || "function" == typeof t ? Z[te.call(t)] || "object" : typeof t
        },
        globalEval: function(e) {
            e && ae.trim(e) && (t.execScript || function(e) {
                t.eval.call(t, e)
            }
            )(e)
        },
        camelCase: function(t) {
            return t.replace(oe, "ms-").replace(se, le)
        },
        nodeName: function(t, e) {
            return t.nodeName && t.nodeName.toLowerCase() === e.toLowerCase()
        },
        each: function(t, e, n) {
            var a, r = 0, o = t.length, s = i(t);
            if (n) {
                if (s)
                    for (; o > r && (a = e.apply(t[r], n),
                    a !== !1); r++)
                        ;
                else
                    for (r in t)
                        if (a = e.apply(t[r], n),
                        a === !1)
                            break
            } else if (s)
                for (; o > r && (a = e.call(t[r], r, t[r]),
                a !== !1); r++)
                    ;
            else
                for (r in t)
                    if (a = e.call(t[r], r, t[r]),
                    a === !1)
                        break;
            return t
        },
        trim: function(t) {
            return null == t ? "" : (t + "").replace(re, "")
        },
        makeArray: function(t, e) {
            var n = e || [];
            return null != t && (i(Object(t)) ? ae.merge(n, "string" == typeof t ? [t] : t) : J.call(n, t)),
            n
        },
        inArray: function(t, e, i) {
            var n;
            if (e) {
                if (K)
                    return K.call(e, t, i);
                for (n = e.length,
                i = i ? 0 > i ? Math.max(0, n + i) : i : 0; n > i; i++)
                    if (i in e && e[i] === t)
                        return i
            }
            return -1
        },
        merge: function(t, e) {
            for (var i = +e.length, n = 0, a = t.length; i > n; )
                t[a++] = e[n++];
            if (i !== i)
                for (; void 0 !== e[n]; )
                    t[a++] = e[n++];
            return t.length = a,
            t
        },
        grep: function(t, e, i) {
            for (var n, a = [], r = 0, o = t.length, s = !i; o > r; r++)
                n = !e(t[r], r),
                n !== s && a.push(t[r]);
            return a
        },
        map: function(t, e, n) {
            var a, r = 0, o = t.length, s = i(t), l = [];
            if (s)
                for (; o > r; r++)
                    a = e(t[r], r, n),
                    null != a && l.push(a);
            else
                for (r in t)
                    a = e(t[r], r, n),
                    null != a && l.push(a);
            return Q.apply([], l)
        },
        guid: 1,
        proxy: function(t, e) {
            var i, n, a;
            return "string" == typeof e && (a = t[e],
            e = t,
            t = a),
            ae.isFunction(t) ? (i = G.call(arguments, 2),
            n = function() {
                return t.apply(e || this, i.concat(G.call(arguments)))
            }
            ,
            n.guid = t.guid = t.guid || ae.guid++,
            n) : void 0
        },
        now: function() {
            return +new Date
        },
        support: ie
    }),
    ae.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function(t, e) {
        Z["[object " + e + "]"] = e.toLowerCase()
    });
    var ce = function(t) {
        function e(t, e, i, n) {
            var a, r, o, s, l, c, d, p, f, m;
            if ((e ? e.ownerDocument || e : F) !== P && E(e),
            e = e || P,
            i = i || [],
            s = e.nodeType,
            "string" != typeof t || !t || 1 !== s && 9 !== s && 11 !== s)
                return i;
            if (!n && M) {
                if (11 !== s && (a = _e.exec(t)))
                    if (o = a[1]) {
                        if (9 === s) {
                            if (r = e.getElementById(o),
                            !r || !r.parentNode)
                                return i;
                            if (r.id === o)
                                return i.push(r),
                                i
                        } else if (e.ownerDocument && (r = e.ownerDocument.getElementById(o)) && L(e, r) && r.id === o)
                            return i.push(r),
                            i
                    } else {
                        if (a[2])
                            return K.apply(i, e.getElementsByTagName(t)),
                            i;
                        if ((o = a[3]) && x.getElementsByClassName)
                            return K.apply(i, e.getElementsByClassName(o)),
                            i
                    }
                if (x.qsa && (!R || !R.test(t))) {
                    if (p = d = j,
                    f = e,
                    m = 1 !== s && t,
                    1 === s && "object" !== e.nodeName.toLowerCase()) {
                        for (c = S(t),
                        (d = e.getAttribute("id")) ? p = d.replace(be, "\\$&") : e.setAttribute("id", p),
                        p = "[id='" + p + "'] ",
                        l = c.length; l--; )
                            c[l] = p + h(c[l]);
                        f = ye.test(t) && u(e.parentNode) || e,
                        m = c.join(",")
                    }
                    if (m)
                        try {
                            return K.apply(i, f.querySelectorAll(m)),
                            i
                        } catch (g) {} finally {
                            d || e.removeAttribute("id")
                        }
                }
            }
            return $(t.replace(le, "$1"), e, i, n)
        }
        function i() {
            function t(i, n) {
                return e.push(i + " ") > w.cacheLength && delete t[e.shift()],
                t[i + " "] = n
            }
            var e = [];
            return t
        }
        function n(t) {
            return t[j] = !0,
            t
        }
        function a(t) {
            var e = P.createElement("div");
            try {
                return !!t(e)
            } catch (i) {
                return !1
            } finally {
                e.parentNode && e.parentNode.removeChild(e),
                e = null
            }
        }
        function r(t, e) {
            for (var i = t.split("|"), n = t.length; n--; )
                w.attrHandle[i[n]] = e
        }
        function o(t, e) {
            var i = e && t
              , n = i && 1 === t.nodeType && 1 === e.nodeType && (~e.sourceIndex || X) - (~t.sourceIndex || X);
            if (n)
                return n;
            if (i)
                for (; i = i.nextSibling; )
                    if (i === e)
                        return -1;
            return t ? 1 : -1
        }
        function s(t) {
            return function(e) {
                var i = e.nodeName.toLowerCase();
                return "input" === i && e.type === t
            }
        }
        function l(t) {
            return function(e) {
                var i = e.nodeName.toLowerCase();
                return ("input" === i || "button" === i) && e.type === t
            }
        }
        function c(t) {
            return n(function(e) {
                return e = +e,
                n(function(i, n) {
                    for (var a, r = t([], i.length, e), o = r.length; o--; )
                        i[a = r[o]] && (i[a] = !(n[a] = i[a]))
                })
            })
        }
        function u(t) {
            return t && "undefined" != typeof t.getElementsByTagName && t
        }
        function d() {}
        function h(t) {
            for (var e = 0, i = t.length, n = ""; i > e; e++)
                n += t[e].value;
            return n
        }
        function p(t, e, i) {
            var n = e.dir
              , a = i && "parentNode" === n
              , r = z++;
            return e.first ? function(e, i, r) {
                for (; e = e[n]; )
                    if (1 === e.nodeType || a)
                        return t(e, i, r)
            }
            : function(e, i, o) {
                var s, l, c = [U, r];
                if (o) {
                    for (; e = e[n]; )
                        if ((1 === e.nodeType || a) && t(e, i, o))
                            return !0
                } else
                    for (; e = e[n]; )
                        if (1 === e.nodeType || a) {
                            if (l = e[j] || (e[j] = {}),
                            (s = l[n]) && s[0] === U && s[1] === r)
                                return c[2] = s[2];
                            if (l[n] = c,
                            c[2] = t(e, i, o))
                                return !0
                        }
            }
        }
        function f(t) {
            return t.length > 1 ? function(e, i, n) {
                for (var a = t.length; a--; )
                    if (!t[a](e, i, n))
                        return !1;
                return !0
            }
            : t[0]
        }
        function m(t, i, n) {
            for (var a = 0, r = i.length; r > a; a++)
                e(t, i[a], n);
            return n
        }
        function g(t, e, i, n, a) {
            for (var r, o = [], s = 0, l = t.length, c = null != e; l > s; s++)
                (r = t[s]) && (!i || i(r, n, a)) && (o.push(r),
                c && e.push(s));
            return o
        }
        function v(t, e, i, a, r, o) {
            return a && !a[j] && (a = v(a)),
            r && !r[j] && (r = v(r, o)),
            n(function(n, o, s, l) {
                var c, u, d, h = [], p = [], f = o.length, v = n || m(e || "*", s.nodeType ? [s] : s, []), _ = !t || !n && e ? v : g(v, h, t, s, l), y = i ? r || (n ? t : f || a) ? [] : o : _;
                if (i && i(_, y, s, l),
                a)
                    for (c = g(y, p),
                    a(c, [], s, l),
                    u = c.length; u--; )
                        (d = c[u]) && (y[p[u]] = !(_[p[u]] = d));
                if (n) {
                    if (r || t) {
                        if (r) {
                            for (c = [],
                            u = y.length; u--; )
                                (d = y[u]) && c.push(_[u] = d);
                            r(null, y = [], c, l)
                        }
                        for (u = y.length; u--; )
                            (d = y[u]) && (c = r ? te(n, d) : h[u]) > -1 && (n[c] = !(o[c] = d))
                    }
                } else
                    y = g(y === o ? y.splice(f, y.length) : y),
                    r ? r(null, o, y, l) : K.apply(o, y)
            })
        }
        function _(t) {
            for (var e, i, n, a = t.length, r = w.relative[t[0].type], o = r || w.relative[" "], s = r ? 1 : 0, l = p(function(t) {
                return t === e
            }, o, !0), c = p(function(t) {
                return te(e, t) > -1
            }, o, !0), u = [function(t, i, n) {
                var a = !r && (n || i !== D) || ((e = i).nodeType ? l(t, i, n) : c(t, i, n));
                return e = null,
                a
            }
            ]; a > s; s++)
                if (i = w.relative[t[s].type])
                    u = [p(f(u), i)];
                else {
                    if (i = w.filter[t[s].type].apply(null, t[s].matches),
                    i[j]) {
                        for (n = ++s; a > n && !w.relative[t[n].type]; n++)
                            ;
                        return v(s > 1 && f(u), s > 1 && h(t.slice(0, s - 1).concat({
                            value: " " === t[s - 2].type ? "*" : ""
                        })).replace(le, "$1"), i, n > s && _(t.slice(s, n)), a > n && _(t = t.slice(n)), a > n && h(t))
                    }
                    u.push(i)
                }
            return f(u)
        }
        function y(t, i) {
            var a = i.length > 0
              , r = t.length > 0
              , o = function(n, o, s, l, c) {
                var u, d, h, p = 0, f = "0", m = n && [], v = [], _ = D, y = n || r && w.find.TAG("*", c), b = U += null == _ ? 1 : Math.random() || .1, x = y.length;
                for (c && (D = o !== P && o); f !== x && null != (u = y[f]); f++) {
                    if (r && u) {
                        for (d = 0; h = t[d++]; )
                            if (h(u, o, s)) {
                                l.push(u);
                                break
                            }
                        c && (U = b)
                    }
                    a && ((u = !h && u) && p--,
                    n && m.push(u))
                }
                if (p += f,
                a && f !== p) {
                    for (d = 0; h = i[d++]; )
                        h(m, v, o, s);
                    if (n) {
                        if (p > 0)
                            for (; f--; )
                                m[f] || v[f] || (v[f] = Q.call(l));
                        v = g(v)
                    }
                    K.apply(l, v),
                    c && !n && v.length > 0 && p + i.length > 1 && e.uniqueSort(l)
                }
                return c && (U = b,
                D = _),
                m
            };
            return a ? n(o) : o
        }
        var b, x, w, T, C, S, k, $, D, A, O, E, P, N, M, R, I, B, L, j = "sizzle" + 1 * new Date, F = t.document, U = 0, z = 0, H = i(), W = i(), q = i(), Y = function(t, e) {
            return t === e && (O = !0),
            0
        }, X = 1 << 31, V = {}.hasOwnProperty, G = [], Q = G.pop, J = G.push, K = G.push, Z = G.slice, te = function(t, e) {
            for (var i = 0, n = t.length; n > i; i++)
                if (t[i] === e)
                    return i;
            return -1
        }, ee = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped", ie = "[\\x20\\t\\r\\n\\f]", ne = "(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+", ae = ne.replace("w", "w#"), re = "\\[" + ie + "*(" + ne + ")(?:" + ie + "*([*^$|!~]?=)" + ie + "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + ae + "))|)" + ie + "*\\]", oe = ":(" + ne + ")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|" + re + ")*)|.*)\\)|)", se = new RegExp(ie + "+","g"), le = new RegExp("^" + ie + "+|((?:^|[^\\\\])(?:\\\\.)*)" + ie + "+$","g"), ce = new RegExp("^" + ie + "*," + ie + "*"), ue = new RegExp("^" + ie + "*([>+~]|" + ie + ")" + ie + "*"), de = new RegExp("=" + ie + "*([^\\]'\"]*?)" + ie + "*\\]","g"), he = new RegExp(oe), pe = new RegExp("^" + ae + "$"), fe = {
            ID: new RegExp("^#(" + ne + ")"),
            CLASS: new RegExp("^\\.(" + ne + ")"),
            TAG: new RegExp("^(" + ne.replace("w", "w*") + ")"),
            ATTR: new RegExp("^" + re),
            PSEUDO: new RegExp("^" + oe),
            CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + ie + "*(even|odd|(([+-]|)(\\d*)n|)" + ie + "*(?:([+-]|)" + ie + "*(\\d+)|))" + ie + "*\\)|)","i"),
            bool: new RegExp("^(?:" + ee + ")$","i"),
            needsContext: new RegExp("^" + ie + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + ie + "*((?:-\\d)?\\d*)" + ie + "*\\)|)(?=[^-]|$)","i")
        }, me = /^(?:input|select|textarea|button)$/i, ge = /^h\d$/i, ve = /^[^{]+\{\s*\[native \w/, _e = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/, ye = /[+~]/, be = /'|\\/g, xe = new RegExp("\\\\([\\da-f]{1,6}" + ie + "?|(" + ie + ")|.)","ig"), we = function(t, e, i) {
            var n = "0x" + e - 65536;
            return n !== n || i ? e : 0 > n ? String.fromCharCode(n + 65536) : String.fromCharCode(n >> 10 | 55296, 1023 & n | 56320)
        }, Te = function() {
            E()
        };
        try {
            K.apply(G = Z.call(F.childNodes), F.childNodes),
            G[F.childNodes.length].nodeType
        } catch (Ce) {
            K = {
                apply: G.length ? function(t, e) {
                    J.apply(t, Z.call(e))
                }
                : function(t, e) {
                    for (var i = t.length, n = 0; t[i++] = e[n++]; )
                        ;
                    t.length = i - 1
                }
            }
        }
        x = e.support = {},
        C = e.isXML = function(t) {
            var e = t && (t.ownerDocument || t).documentElement;
            return e ? "HTML" !== e.nodeName : !1
        }
        ,
        E = e.setDocument = function(t) {
            var e, i, n = t ? t.ownerDocument || t : F;
            return n !== P && 9 === n.nodeType && n.documentElement ? (P = n,
            N = n.documentElement,
            i = n.defaultView,
            i && i !== i.top && (i.addEventListener ? i.addEventListener("unload", Te, !1) : i.attachEvent && i.attachEvent("onunload", Te)),
            M = !C(n),
            x.attributes = a(function(t) {
                return t.className = "i",
                !t.getAttribute("className")
            }),
            x.getElementsByTagName = a(function(t) {
                return t.appendChild(n.createComment("")),
                !t.getElementsByTagName("*").length
            }),
            x.getElementsByClassName = ve.test(n.getElementsByClassName),
            x.getById = a(function(t) {
                return N.appendChild(t).id = j,
                !n.getElementsByName || !n.getElementsByName(j).length
            }),
            x.getById ? (w.find.ID = function(t, e) {
                if ("undefined" != typeof e.getElementById && M) {
                    var i = e.getElementById(t);
                    return i && i.parentNode ? [i] : []
                }
            }
            ,
            w.filter.ID = function(t) {
                var e = t.replace(xe, we);
                return function(t) {
                    return t.getAttribute("id") === e
                }
            }
            ) : (delete w.find.ID,
            w.filter.ID = function(t) {
                var e = t.replace(xe, we);
                return function(t) {
                    var i = "undefined" != typeof t.getAttributeNode && t.getAttributeNode("id");
                    return i && i.value === e
                }
            }
            ),
            w.find.TAG = x.getElementsByTagName ? function(t, e) {
                return "undefined" != typeof e.getElementsByTagName ? e.getElementsByTagName(t) : x.qsa ? e.querySelectorAll(t) : void 0
            }
            : function(t, e) {
                var i, n = [], a = 0, r = e.getElementsByTagName(t);
                if ("*" === t) {
                    for (; i = r[a++]; )
                        1 === i.nodeType && n.push(i);
                    return n
                }
                return r
            }
            ,
            w.find.CLASS = x.getElementsByClassName && function(t, e) {
                return M ? e.getElementsByClassName(t) : void 0
            }
            ,
            I = [],
            R = [],
            (x.qsa = ve.test(n.querySelectorAll)) && (a(function(t) {
                N.appendChild(t).innerHTML = "<a id='" + j + "'></a><select id='" + j + "-\f]' msallowcapture=''><option selected=''></option></select>",
                t.querySelectorAll("[msallowcapture^='']").length && R.push("[*^$]=" + ie + "*(?:''|\"\")"),
                t.querySelectorAll("[selected]").length || R.push("\\[" + ie + "*(?:value|" + ee + ")"),
                t.querySelectorAll("[id~=" + j + "-]").length || R.push("~="),
                t.querySelectorAll(":checked").length || R.push(":checked"),
                t.querySelectorAll("a#" + j + "+*").length || R.push(".#.+[+~]")
            }),
            a(function(t) {
                var e = n.createElement("input");
                e.setAttribute("type", "hidden"),
                t.appendChild(e).setAttribute("name", "D"),
                t.querySelectorAll("[name=d]").length && R.push("name" + ie + "*[*^$|!~]?="),
                t.querySelectorAll(":enabled").length || R.push(":enabled", ":disabled"),
                t.querySelectorAll("*,:x"),
                R.push(",.*:")
            })),
            (x.matchesSelector = ve.test(B = N.matches || N.webkitMatchesSelector || N.mozMatchesSelector || N.oMatchesSelector || N.msMatchesSelector)) && a(function(t) {
                x.disconnectedMatch = B.call(t, "div"),
                B.call(t, "[s!='']:x"),
                I.push("!=", oe)
            }),
            R = R.length && new RegExp(R.join("|")),
            I = I.length && new RegExp(I.join("|")),
            e = ve.test(N.compareDocumentPosition),
            L = e || ve.test(N.contains) ? function(t, e) {
                var i = 9 === t.nodeType ? t.documentElement : t
                  , n = e && e.parentNode;
                return t === n || !(!n || 1 !== n.nodeType || !(i.contains ? i.contains(n) : t.compareDocumentPosition && 16 & t.compareDocumentPosition(n)))
            }
            : function(t, e) {
                if (e)
                    for (; e = e.parentNode; )
                        if (e === t)
                            return !0;
                return !1
            }
            ,
            Y = e ? function(t, e) {
                if (t === e)
                    return O = !0,
                    0;
                var i = !t.compareDocumentPosition - !e.compareDocumentPosition;
                return i ? i : (i = (t.ownerDocument || t) === (e.ownerDocument || e) ? t.compareDocumentPosition(e) : 1,
                1 & i || !x.sortDetached && e.compareDocumentPosition(t) === i ? t === n || t.ownerDocument === F && L(F, t) ? -1 : e === n || e.ownerDocument === F && L(F, e) ? 1 : A ? te(A, t) - te(A, e) : 0 : 4 & i ? -1 : 1)
            }
            : function(t, e) {
                if (t === e)
                    return O = !0,
                    0;
                var i, a = 0, r = t.parentNode, s = e.parentNode, l = [t], c = [e];
                if (!r || !s)
                    return t === n ? -1 : e === n ? 1 : r ? -1 : s ? 1 : A ? te(A, t) - te(A, e) : 0;
                if (r === s)
                    return o(t, e);
                for (i = t; i = i.parentNode; )
                    l.unshift(i);
                for (i = e; i = i.parentNode; )
                    c.unshift(i);
                for (; l[a] === c[a]; )
                    a++;
                return a ? o(l[a], c[a]) : l[a] === F ? -1 : c[a] === F ? 1 : 0
            }
            ,
            n) : P
        }
        ,
        e.matches = function(t, i) {
            return e(t, null, null, i)
        }
        ,
        e.matchesSelector = function(t, i) {
            if ((t.ownerDocument || t) !== P && E(t),
            i = i.replace(de, "='$1']"),
            !(!x.matchesSelector || !M || I && I.test(i) || R && R.test(i)))
                try {
                    var n = B.call(t, i);
                    if (n || x.disconnectedMatch || t.document && 11 !== t.document.nodeType)
                        return n
                } catch (a) {}
            return e(i, P, null, [t]).length > 0
        }
        ,
        e.contains = function(t, e) {
            return (t.ownerDocument || t) !== P && E(t),
            L(t, e)
        }
        ,
        e.attr = function(t, e) {
            (t.ownerDocument || t) !== P && E(t);
            var i = w.attrHandle[e.toLowerCase()]
              , n = i && V.call(w.attrHandle, e.toLowerCase()) ? i(t, e, !M) : void 0;
            return void 0 !== n ? n : x.attributes || !M ? t.getAttribute(e) : (n = t.getAttributeNode(e)) && n.specified ? n.value : null
        }
        ,
        e.error = function(t) {
            throw new Error("Syntax error, unrecognized expression: " + t)
        }
        ,
        e.uniqueSort = function(t) {
            var e, i = [], n = 0, a = 0;
            if (O = !x.detectDuplicates,
            A = !x.sortStable && t.slice(0),
            t.sort(Y),
            O) {
                for (; e = t[a++]; )
                    e === t[a] && (n = i.push(a));
                for (; n--; )
                    t.splice(i[n], 1)
            }
            return A = null,
            t
        }
        ,
        T = e.getText = function(t) {
            var e, i = "", n = 0, a = t.nodeType;
            if (a) {
                if (1 === a || 9 === a || 11 === a) {
                    if ("string" == typeof t.textContent)
                        return t.textContent;
                    for (t = t.firstChild; t; t = t.nextSibling)
                        i += T(t)
                } else if (3 === a || 4 === a)
                    return t.nodeValue
            } else
                for (; e = t[n++]; )
                    i += T(e);
            return i
        }
        ,
        w = e.selectors = {
            cacheLength: 50,
            createPseudo: n,
            match: fe,
            attrHandle: {},
            find: {},
            relative: {
                ">": {
                    dir: "parentNode",
                    first: !0
                },
                " ": {
                    dir: "parentNode"
                },
                "+": {
                    dir: "previousSibling",
                    first: !0
                },
                "~": {
                    dir: "previousSibling"
                }
            },
            preFilter: {
                ATTR: function(t) {
                    return t[1] = t[1].replace(xe, we),
                    t[3] = (t[3] || t[4] || t[5] || "").replace(xe, we),
                    "~=" === t[2] && (t[3] = " " + t[3] + " "),
                    t.slice(0, 4)
                },
                CHILD: function(t) {
                    return t[1] = t[1].toLowerCase(),
                    "nth" === t[1].slice(0, 3) ? (t[3] || e.error(t[0]),
                    t[4] = +(t[4] ? t[5] + (t[6] || 1) : 2 * ("even" === t[3] || "odd" === t[3])),
                    t[5] = +(t[7] + t[8] || "odd" === t[3])) : t[3] && e.error(t[0]),
                    t
                },
                PSEUDO: function(t) {
                    var e, i = !t[6] && t[2];
                    return fe.CHILD.test(t[0]) ? null : (t[3] ? t[2] = t[4] || t[5] || "" : i && he.test(i) && (e = S(i, !0)) && (e = i.indexOf(")", i.length - e) - i.length) && (t[0] = t[0].slice(0, e),
                    t[2] = i.slice(0, e)),
                    t.slice(0, 3))
                }
            },
            filter: {
                TAG: function(t) {
                    var e = t.replace(xe, we).toLowerCase();
                    return "*" === t ? function() {
                        return !0
                    }
                    : function(t) {
                        return t.nodeName && t.nodeName.toLowerCase() === e
                    }
                },
                CLASS: function(t) {
                    var e = H[t + " "];
                    return e || (e = new RegExp("(^|" + ie + ")" + t + "(" + ie + "|$)")) && H(t, function(t) {
                        return e.test("string" == typeof t.className && t.className || "undefined" != typeof t.getAttribute && t.getAttribute("class") || "")
                    })
                },
                ATTR: function(t, i, n) {
                    return function(a) {
                        var r = e.attr(a, t);
                        return null == r ? "!=" === i : i ? (r += "",
                        "=" === i ? r === n : "!=" === i ? r !== n : "^=" === i ? n && 0 === r.indexOf(n) : "*=" === i ? n && r.indexOf(n) > -1 : "$=" === i ? n && r.slice(-n.length) === n : "~=" === i ? (" " + r.replace(se, " ") + " ").indexOf(n) > -1 : "|=" === i ? r === n || r.slice(0, n.length + 1) === n + "-" : !1) : !0
                    }
                },
                CHILD: function(t, e, i, n, a) {
                    var r = "nth" !== t.slice(0, 3)
                      , o = "last" !== t.slice(-4)
                      , s = "of-type" === e;
                    return 1 === n && 0 === a ? function(t) {
                        return !!t.parentNode
                    }
                    : function(e, i, l) {
                        var c, u, d, h, p, f, m = r !== o ? "nextSibling" : "previousSibling", g = e.parentNode, v = s && e.nodeName.toLowerCase(), _ = !l && !s;
                        if (g) {
                            if (r) {
                                for (; m; ) {
                                    for (d = e; d = d[m]; )
                                        if (s ? d.nodeName.toLowerCase() === v : 1 === d.nodeType)
                                            return !1;
                                    f = m = "only" === t && !f && "nextSibling"
                                }
                                return !0
                            }
                            if (f = [o ? g.firstChild : g.lastChild],
                            o && _) {
                                for (u = g[j] || (g[j] = {}),
                                c = u[t] || [],
                                p = c[0] === U && c[1],
                                h = c[0] === U && c[2],
                                d = p && g.childNodes[p]; d = ++p && d && d[m] || (h = p = 0) || f.pop(); )
                                    if (1 === d.nodeType && ++h && d === e) {
                                        u[t] = [U, p, h];
                                        break
                                    }
                            } else if (_ && (c = (e[j] || (e[j] = {}))[t]) && c[0] === U)
                                h = c[1];
                            else
                                for (; (d = ++p && d && d[m] || (h = p = 0) || f.pop()) && ((s ? d.nodeName.toLowerCase() !== v : 1 !== d.nodeType) || !++h || (_ && ((d[j] || (d[j] = {}))[t] = [U, h]),
                                d !== e)); )
                                    ;
                            return h -= a,
                            h === n || h % n === 0 && h / n >= 0
                        }
                    }
                },
                PSEUDO: function(t, i) {
                    var a, r = w.pseudos[t] || w.setFilters[t.toLowerCase()] || e.error("unsupported pseudo: " + t);
                    return r[j] ? r(i) : r.length > 1 ? (a = [t, t, "", i],
                    w.setFilters.hasOwnProperty(t.toLowerCase()) ? n(function(t, e) {
                        for (var n, a = r(t, i), o = a.length; o--; )
                            n = te(t, a[o]),
                            t[n] = !(e[n] = a[o])
                    }) : function(t) {
                        return r(t, 0, a)
                    }
                    ) : r
                }
            },
            pseudos: {
                not: n(function(t) {
                    var e = []
                      , i = []
                      , a = k(t.replace(le, "$1"));
                    return a[j] ? n(function(t, e, i, n) {
                        for (var r, o = a(t, null, n, []), s = t.length; s--; )
                            (r = o[s]) && (t[s] = !(e[s] = r))
                    }) : function(t, n, r) {
                        return e[0] = t,
                        a(e, null, r, i),
                        e[0] = null,
                        !i.pop()
                    }
                }),
                has: n(function(t) {
                    return function(i) {
                        return e(t, i).length > 0
                    }
                }),
                contains: n(function(t) {
                    return t = t.replace(xe, we),
                    function(e) {
                        return (e.textContent || e.innerText || T(e)).indexOf(t) > -1
                    }
                }),
                lang: n(function(t) {
                    return pe.test(t || "") || e.error("unsupported lang: " + t),
                    t = t.replace(xe, we).toLowerCase(),
                    function(e) {
                        var i;
                        do
                            if (i = M ? e.lang : e.getAttribute("xml:lang") || e.getAttribute("lang"))
                                return i = i.toLowerCase(),
                                i === t || 0 === i.indexOf(t + "-");
                        while ((e = e.parentNode) && 1 === e.nodeType);return !1
                    }
                }),
                target: function(e) {
                    var i = t.location && t.location.hash;
                    return i && i.slice(1) === e.id
                },
                root: function(t) {
                    return t === N
                },
                focus: function(t) {
                    return t === P.activeElement && (!P.hasFocus || P.hasFocus()) && !!(t.type || t.href || ~t.tabIndex)
                },
                enabled: function(t) {
                    return t.disabled === !1
                },
                disabled: function(t) {
                    return t.disabled === !0
                },
                checked: function(t) {
                    var e = t.nodeName.toLowerCase();
                    return "input" === e && !!t.checked || "option" === e && !!t.selected
                },
                selected: function(t) {
                    return t.parentNode && t.parentNode.selectedIndex,
                    t.selected === !0
                },
                empty: function(t) {
                    for (t = t.firstChild; t; t = t.nextSibling)
                        if (t.nodeType < 6)
                            return !1;
                    return !0
                },
                parent: function(t) {
                    return !w.pseudos.empty(t)
                },
                header: function(t) {
                    return ge.test(t.nodeName)
                },
                input: function(t) {
                    return me.test(t.nodeName)
                },
                button: function(t) {
                    var e = t.nodeName.toLowerCase();
                    return "input" === e && "button" === t.type || "button" === e
                },
                text: function(t) {
                    var e;
                    return "input" === t.nodeName.toLowerCase() && "text" === t.type && (null == (e = t.getAttribute("type")) || "text" === e.toLowerCase())
                },
                first: c(function() {
                    return [0]
                }),
                last: c(function(t, e) {
                    return [e - 1]
                }),
                eq: c(function(t, e, i) {
                    return [0 > i ? i + e : i]
                }),
                even: c(function(t, e) {
                    for (var i = 0; e > i; i += 2)
                        t.push(i);
                    return t
                }),
                odd: c(function(t, e) {
                    for (var i = 1; e > i; i += 2)
                        t.push(i);
                    return t
                }),
                lt: c(function(t, e, i) {
                    for (var n = 0 > i ? i + e : i; --n >= 0; )
                        t.push(n);
                    return t
                }),
                gt: c(function(t, e, i) {
                    for (var n = 0 > i ? i + e : i; ++n < e; )
                        t.push(n);
                    return t
                })
            }
        },
        w.pseudos.nth = w.pseudos.eq;
        for (b in {
            radio: !0,
            checkbox: !0,
            file: !0,
            password: !0,
            image: !0
        })
            w.pseudos[b] = s(b);
        for (b in {
            submit: !0,
            reset: !0
        })
            w.pseudos[b] = l(b);
        return d.prototype = w.filters = w.pseudos,
        w.setFilters = new d,
        S = e.tokenize = function(t, i) {
            var n, a, r, o, s, l, c, u = W[t + " "];
            if (u)
                return i ? 0 : u.slice(0);
            for (s = t,
            l = [],
            c = w.preFilter; s; ) {
                (!n || (a = ce.exec(s))) && (a && (s = s.slice(a[0].length) || s),
                l.push(r = [])),
                n = !1,
                (a = ue.exec(s)) && (n = a.shift(),
                r.push({
                    value: n,
                    type: a[0].replace(le, " ")
                }),
                s = s.slice(n.length));
                for (o in w.filter)
                    !(a = fe[o].exec(s)) || c[o] && !(a = c[o](a)) || (n = a.shift(),
                    r.push({
                        value: n,
                        type: o,
                        matches: a
                    }),
                    s = s.slice(n.length));
                if (!n)
                    break
            }
            return i ? s.length : s ? e.error(t) : W(t, l).slice(0)
        }
        ,
        k = e.compile = function(t, e) {
            var i, n = [], a = [], r = q[t + " "];
            if (!r) {
                for (e || (e = S(t)),
                i = e.length; i--; )
                    r = _(e[i]),
                    r[j] ? n.push(r) : a.push(r);
                r = q(t, y(a, n)),
                r.selector = t
            }
            return r
        }
        ,
        $ = e.select = function(t, e, i, n) {
            var a, r, o, s, l, c = "function" == typeof t && t, d = !n && S(t = c.selector || t);
            if (i = i || [],
            1 === d.length) {
                if (r = d[0] = d[0].slice(0),
                r.length > 2 && "ID" === (o = r[0]).type && x.getById && 9 === e.nodeType && M && w.relative[r[1].type]) {
                    if (e = (w.find.ID(o.matches[0].replace(xe, we), e) || [])[0],
                    !e)
                        return i;
                    c && (e = e.parentNode),
                    t = t.slice(r.shift().value.length)
                }
                for (a = fe.needsContext.test(t) ? 0 : r.length; a-- && (o = r[a],
                !w.relative[s = o.type]); )
                    if ((l = w.find[s]) && (n = l(o.matches[0].replace(xe, we), ye.test(r[0].type) && u(e.parentNode) || e))) {
                        if (r.splice(a, 1),
                        t = n.length && h(r),
                        !t)
                            return K.apply(i, n),
                            i;
                        break
                    }
            }
            return (c || k(t, d))(n, e, !M, i, ye.test(t) && u(e.parentNode) || e),
            i
        }
        ,
        x.sortStable = j.split("").sort(Y).join("") === j,
        x.detectDuplicates = !!O,
        E(),
        x.sortDetached = a(function(t) {
            return 1 & t.compareDocumentPosition(P.createElement("div"))
        }),
        a(function(t) {
            return t.innerHTML = "<a href='#'></a>",
            "#" === t.firstChild.getAttribute("href")
        }) || r("type|href|height|width", function(t, e, i) {
            return i ? void 0 : t.getAttribute(e, "type" === e.toLowerCase() ? 1 : 2)
        }),
        x.attributes && a(function(t) {
            return t.innerHTML = "<input/>",
            t.firstChild.setAttribute("value", ""),
            "" === t.firstChild.getAttribute("value")
        }) || r("value", function(t, e, i) {
            return i || "input" !== t.nodeName.toLowerCase() ? void 0 : t.defaultValue
        }),
        a(function(t) {
            return null == t.getAttribute("disabled")
        }) || r(ee, function(t, e, i) {
            var n;
            return i ? void 0 : t[e] === !0 ? e.toLowerCase() : (n = t.getAttributeNode(e)) && n.specified ? n.value : null
        }),
        e
    }(t);
    ae.find = ce,
    ae.expr = ce.selectors,
    ae.expr[":"] = ae.expr.pseudos,
    ae.unique = ce.uniqueSort,
    ae.text = ce.getText,
    ae.isXMLDoc = ce.isXML,
    ae.contains = ce.contains;
    var ue = ae.expr.match.needsContext
      , de = /^<(\w+)\s*\/?>(?:<\/\1>|)$/
      , he = /^.[^:#\[\.,]*$/;
    ae.filter = function(t, e, i) {
        var n = e[0];
        return i && (t = ":not(" + t + ")"),
        1 === e.length && 1 === n.nodeType ? ae.find.matchesSelector(n, t) ? [n] : [] : ae.find.matches(t, ae.grep(e, function(t) {
            return 1 === t.nodeType
        }))
    }
    ,
    ae.fn.extend({
        find: function(t) {
            var e, i = [], n = this, a = n.length;
            if ("string" != typeof t)
                return this.pushStack(ae(t).filter(function() {
                    for (e = 0; a > e; e++)
                        if (ae.contains(n[e], this))
                            return !0
                }));
            for (e = 0; a > e; e++)
                ae.find(t, n[e], i);
            return i = this.pushStack(a > 1 ? ae.unique(i) : i),
            i.selector = this.selector ? this.selector + " " + t : t,
            i
        },
        filter: function(t) {
            return this.pushStack(n(this, t || [], !1))
        },
        not: function(t) {
            return this.pushStack(n(this, t || [], !0))
        },
        is: function(t) {
            return !!n(this, "string" == typeof t && ue.test(t) ? ae(t) : t || [], !1).length
        }
    });
    var pe, fe = t.document, me = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/, ge = ae.fn.init = function(t, e) {
        var i, n;
        if (!t)
            return this;
        if ("string" == typeof t) {
            if (i = "<" === t.charAt(0) && ">" === t.charAt(t.length - 1) && t.length >= 3 ? [null, t, null] : me.exec(t),
            !i || !i[1] && e)
                return !e || e.jquery ? (e || pe).find(t) : this.constructor(e).find(t);
            if (i[1]) {
                if (e = e instanceof ae ? e[0] : e,
                ae.merge(this, ae.parseHTML(i[1], e && e.nodeType ? e.ownerDocument || e : fe, !0)),
                de.test(i[1]) && ae.isPlainObject(e))
                    for (i in e)
                        ae.isFunction(this[i]) ? this[i](e[i]) : this.attr(i, e[i]);
                return this
            }
            if (n = fe.getElementById(i[2]),
            n && n.parentNode) {
                if (n.id !== i[2])
                    return pe.find(t);
                this.length = 1,
                this[0] = n
            }
            return this.context = fe,
            this.selector = t,
            this
        }
        return t.nodeType ? (this.context = this[0] = t,
        this.length = 1,
        this) : ae.isFunction(t) ? "undefined" != typeof pe.ready ? pe.ready(t) : t(ae) : (void 0 !== t.selector && (this.selector = t.selector,
        this.context = t.context),
        ae.makeArray(t, this))
    }
    ;
    ge.prototype = ae.fn,
    pe = ae(fe);
    var ve = /^(?:parents|prev(?:Until|All))/
      , _e = {
        children: !0,
        contents: !0,
        next: !0,
        prev: !0
    };
    ae.extend({
        dir: function(t, e, i) {
            for (var n = [], a = t[e]; a && 9 !== a.nodeType && (void 0 === i || 1 !== a.nodeType || !ae(a).is(i)); )
                1 === a.nodeType && n.push(a),
                a = a[e];
            return n
        },
        sibling: function(t, e) {
            for (var i = []; t; t = t.nextSibling)
                1 === t.nodeType && t !== e && i.push(t);
            return i
        }
    }),
    ae.fn.extend({
        has: function(t) {
            var e, i = ae(t, this), n = i.length;
            return this.filter(function() {
                for (e = 0; n > e; e++)
                    if (ae.contains(this, i[e]))
                        return !0
            })
        },
        closest: function(t, e) {
            for (var i, n = 0, a = this.length, r = [], o = ue.test(t) || "string" != typeof t ? ae(t, e || this.context) : 0; a > n; n++)
                for (i = this[n]; i && i !== e; i = i.parentNode)
                    if (i.nodeType < 11 && (o ? o.index(i) > -1 : 1 === i.nodeType && ae.find.matchesSelector(i, t))) {
                        r.push(i);
                        break
                    }
            return this.pushStack(r.length > 1 ? ae.unique(r) : r)
        },
        index: function(t) {
            return t ? "string" == typeof t ? ae.inArray(this[0], ae(t)) : ae.inArray(t.jquery ? t[0] : t, this) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1
        },
        add: function(t, e) {
            return this.pushStack(ae.unique(ae.merge(this.get(), ae(t, e))))
        },
        addBack: function(t) {
            return this.add(null == t ? this.prevObject : this.prevObject.filter(t))
        }
    }),
    ae.each({
        parent: function(t) {
            var e = t.parentNode;
            return e && 11 !== e.nodeType ? e : null
        },
        parents: function(t) {
            return ae.dir(t, "parentNode")
        },
        parentsUntil: function(t, e, i) {
            return ae.dir(t, "parentNode", i)
        },
        next: function(t) {
            return a(t, "nextSibling")
        },
        prev: function(t) {
            return a(t, "previousSibling")
        },
        nextAll: function(t) {
            return ae.dir(t, "nextSibling")
        },
        prevAll: function(t) {
            return ae.dir(t, "previousSibling")
        },
        nextUntil: function(t, e, i) {
            return ae.dir(t, "nextSibling", i)
        },
        prevUntil: function(t, e, i) {
            return ae.dir(t, "previousSibling", i)
        },
        siblings: function(t) {
            return ae.sibling((t.parentNode || {}).firstChild, t)
        },
        children: function(t) {
            return ae.sibling(t.firstChild)
        },
        contents: function(t) {
            return ae.nodeName(t, "iframe") ? t.contentDocument || t.contentWindow.document : ae.merge([], t.childNodes)
        }
    }, function(t, e) {
        ae.fn[t] = function(i, n) {
            var a = ae.map(this, e, i);
            return "Until" !== t.slice(-5) && (n = i),
            n && "string" == typeof n && (a = ae.filter(n, a)),
            this.length > 1 && (_e[t] || (a = ae.unique(a)),
            ve.test(t) && (a = a.reverse())),
            this.pushStack(a)
        }
    });
    var ye = /\S+/g
      , be = {};
    ae.Callbacks = function(t) {
        t = "string" == typeof t ? be[t] || r(t) : ae.extend({}, t);
        var e, i, n, a, o, s, l = [], c = !t.once && [], u = function(r) {
            for (i = t.memory && r,
            n = !0,
            o = s || 0,
            s = 0,
            a = l.length,
            e = !0; l && a > o; o++)
                if (l[o].apply(r[0], r[1]) === !1 && t.stopOnFalse) {
                    i = !1;
                    break
                }
            e = !1,
            l && (c ? c.length && u(c.shift()) : i ? l = [] : d.disable())
        }, d = {
            add: function() {
                if (l) {
                    var n = l.length;
                    !function r(e) {
                        ae.each(e, function(e, i) {
                            var n = ae.type(i);
                            "function" === n ? t.unique && d.has(i) || l.push(i) : i && i.length && "string" !== n && r(i)
                        })
                    }(arguments),
                    e ? a = l.length : i && (s = n,
                    u(i))
                }
                return this
            },
            remove: function() {
                return l && ae.each(arguments, function(t, i) {
                    for (var n; (n = ae.inArray(i, l, n)) > -1; )
                        l.splice(n, 1),
                        e && (a >= n && a--,
                        o >= n && o--)
                }),
                this
            },
            has: function(t) {
                return t ? ae.inArray(t, l) > -1 : !(!l || !l.length)
            },
            empty: function() {
                return l = [],
                a = 0,
                this
            },
            disable: function() {
                return l = c = i = void 0,
                this
            },
            disabled: function() {
                return !l
            },
            lock: function() {
                return c = void 0,
                i || d.disable(),
                this
            },
            locked: function() {
                return !c
            },
            fireWith: function(t, i) {
                return !l || n && !c || (i = i || [],
                i = [t, i.slice ? i.slice() : i],
                e ? c.push(i) : u(i)),
                this
            },
            fire: function() {
                return d.fireWith(this, arguments),
                this
            },
            fired: function() {
                return !!n
            }
        };
        return d
    }
    ,
    ae.extend({
        Deferred: function(t) {
            var e = [["resolve", "done", ae.Callbacks("once memory"), "resolved"], ["reject", "fail", ae.Callbacks("once memory"), "rejected"], ["notify", "progress", ae.Callbacks("memory")]]
              , i = "pending"
              , n = {
                state: function() {
                    return i
                },
                always: function() {
                    return a.done(arguments).fail(arguments),
                    this
                },
                then: function() {
                    var t = arguments;
                    return ae.Deferred(function(i) {
                        ae.each(e, function(e, r) {
                            var o = ae.isFunction(t[e]) && t[e];
                            a[r[1]](function() {
                                var t = o && o.apply(this, arguments);
                                t && ae.isFunction(t.promise) ? t.promise().done(i.resolve).fail(i.reject).progress(i.notify) : i[r[0] + "With"](this === n ? i.promise() : this, o ? [t] : arguments)
                            })
                        }),
                        t = null
                    }).promise()
                },
                promise: function(t) {
                    return null != t ? ae.extend(t, n) : n
                }
            }
              , a = {};
            return n.pipe = n.then,
            ae.each(e, function(t, r) {
                var o = r[2]
                  , s = r[3];
                n[r[1]] = o.add,
                s && o.add(function() {
                    i = s
                }, e[1 ^ t][2].disable, e[2][2].lock),
                a[r[0]] = function() {
                    return a[r[0] + "With"](this === a ? n : this, arguments),
                    this
                }
                ,
                a[r[0] + "With"] = o.fireWith
            }),
            n.promise(a),
            t && t.call(a, a),
            a
        },
        when: function(t) {
            var e, i, n, a = 0, r = G.call(arguments), o = r.length, s = 1 !== o || t && ae.isFunction(t.promise) ? o : 0, l = 1 === s ? t : ae.Deferred(), c = function(t, i, n) {
                return function(a) {
                    i[t] = this,
                    n[t] = arguments.length > 1 ? G.call(arguments) : a,
                    n === e ? l.notifyWith(i, n) : --s || l.resolveWith(i, n)
                }
            };
            if (o > 1)
                for (e = new Array(o),
                i = new Array(o),
                n = new Array(o); o > a; a++)
                    r[a] && ae.isFunction(r[a].promise) ? r[a].promise().done(c(a, n, r)).fail(l.reject).progress(c(a, i, e)) : --s;
            return s || l.resolveWith(n, r),
            l.promise()
        }
    });
    var xe;
    ae.fn.ready = function(t) {
        return ae.ready.promise().done(t),
        this
    }
    ,
    ae.extend({
        isReady: !1,
        readyWait: 1,
        holdReady: function(t) {
            t ? ae.readyWait++ : ae.ready(!0)
        },
        ready: function(t) {
            if (t === !0 ? !--ae.readyWait : !ae.isReady) {
                if (!fe.body)
                    return setTimeout(ae.ready);
                ae.isReady = !0,
                t !== !0 && --ae.readyWait > 0 || (xe.resolveWith(fe, [ae]),
                ae.fn.triggerHandler && (ae(fe).triggerHandler("ready"),
                ae(fe).off("ready")))
            }
        }
    }),
    ae.ready.promise = function(e) {
        if (!xe)
            if (xe = ae.Deferred(),
            "complete" === fe.readyState)
                setTimeout(ae.ready);
            else if (fe.addEventListener)
                fe.addEventListener("DOMContentLoaded", s, !1),
                t.addEventListener("load", s, !1);
            else {
                fe.attachEvent("onreadystatechange", s),
                t.attachEvent("onload", s);
                var i = !1;
                try {
                    i = null == t.frameElement && fe.documentElement
                } catch (n) {}
                i && i.doScroll && !function a() {
                    if (!ae.isReady) {
                        try {
                            i.doScroll("left")
                        } catch (t) {
                            return setTimeout(a, 50)
                        }
                        o(),
                        ae.ready()
                    }
                }()
            }
        return xe.promise(e)
    }
    ;
    var we, Te = "undefined";
    for (we in ae(ie))
        break;
    ie.ownLast = "0" !== we,
    ie.inlineBlockNeedsLayout = !1,
    ae(function() {
        var t, e, i, n;
        i = fe.getElementsByTagName("body")[0],
        i && i.style && (e = fe.createElement("div"),
        n = fe.createElement("div"),
        n.style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px",
        i.appendChild(n).appendChild(e),
        typeof e.style.zoom !== Te && (e.style.cssText = "display:inline;margin:0;border:0;padding:1px;width:1px;zoom:1",
        ie.inlineBlockNeedsLayout = t = 3 === e.offsetWidth,
        t && (i.style.zoom = 1)),
        i.removeChild(n))
    }),
    function() {
        var t = fe.createElement("div");
        if (null == ie.deleteExpando) {
            ie.deleteExpando = !0;
            try {
                delete t.test
            } catch (e) {
                ie.deleteExpando = !1
            }
        }
        t = null
    }(),
    ae.acceptData = function(t) {
        var e = ae.noData[(t.nodeName + " ").toLowerCase()]
          , i = +t.nodeType || 1;
        return 1 !== i && 9 !== i ? !1 : !e || e !== !0 && t.getAttribute("classid") === e
    }
    ;
    var Ce = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/
      , Se = /([A-Z])/g;
    ae.extend({
        cache: {},
        noData: {
            "applet ": !0,
            "embed ": !0,
            "object ": "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
        },
        hasData: function(t) {
            return t = t.nodeType ? ae.cache[t[ae.expando]] : t[ae.expando],
            !!t && !c(t)
        },
        data: function(t, e, i) {
            return u(t, e, i)
        },
        removeData: function(t, e) {
            return d(t, e)
        },
        _data: function(t, e, i) {
            return u(t, e, i, !0)
        },
        _removeData: function(t, e) {
            return d(t, e, !0)
        }
    }),
    ae.fn.extend({
        data: function(t, e) {
            var i, n, a, r = this[0], o = r && r.attributes;
            if (void 0 === t) {
                if (this.length && (a = ae.data(r),
                1 === r.nodeType && !ae._data(r, "parsedAttrs"))) {
                    for (i = o.length; i--; )
                        o[i] && (n = o[i].name,
                        0 === n.indexOf("data-") && (n = ae.camelCase(n.slice(5)),
                        l(r, n, a[n])));
                    ae._data(r, "parsedAttrs", !0)
                }
                return a
            }
            return "object" == typeof t ? this.each(function() {
                ae.data(this, t)
            }) : arguments.length > 1 ? this.each(function() {
                ae.data(this, t, e)
            }) : r ? l(r, t, ae.data(r, t)) : void 0
        },
        removeData: function(t) {
            return this.each(function() {
                ae.removeData(this, t)
            })
        }
    }),
    ae.extend({
        queue: function(t, e, i) {
            var n;
            return t ? (e = (e || "fx") + "queue",
            n = ae._data(t, e),
            i && (!n || ae.isArray(i) ? n = ae._data(t, e, ae.makeArray(i)) : n.push(i)),
            n || []) : void 0
        },
        dequeue: function(t, e) {
            e = e || "fx";
            var i = ae.queue(t, e)
              , n = i.length
              , a = i.shift()
              , r = ae._queueHooks(t, e)
              , o = function() {
                ae.dequeue(t, e)
            };
            "inprogress" === a && (a = i.shift(),
            n--),
            a && ("fx" === e && i.unshift("inprogress"),
            delete r.stop,
            a.call(t, o, r)),
            !n && r && r.empty.fire()
        },
        _queueHooks: function(t, e) {
            var i = e + "queueHooks";
            return ae._data(t, i) || ae._data(t, i, {
                empty: ae.Callbacks("once memory").add(function() {
                    ae._removeData(t, e + "queue"),
                    ae._removeData(t, i)
                })
            })
        }
    }),
    ae.fn.extend({
        queue: function(t, e) {
            var i = 2;
            return "string" != typeof t && (e = t,
            t = "fx",
            i--),
            arguments.length < i ? ae.queue(this[0], t) : void 0 === e ? this : this.each(function() {
                var i = ae.queue(this, t, e);
                ae._queueHooks(this, t),
                "fx" === t && "inprogress" !== i[0] && ae.dequeue(this, t)
            })
        },
        dequeue: function(t) {
            return this.each(function() {
                ae.dequeue(this, t)
            })
        },
        clearQueue: function(t) {
            return this.queue(t || "fx", [])
        },
        promise: function(t, e) {
            var i, n = 1, a = ae.Deferred(), r = this, o = this.length, s = function() {
                --n || a.resolveWith(r, [r])
            };
            for ("string" != typeof t && (e = t,
            t = void 0),
            t = t || "fx"; o--; )
                i = ae._data(r[o], t + "queueHooks"),
                i && i.empty && (n++,
                i.empty.add(s));
            return s(),
            a.promise(e)
        }
    });
    var ke = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source
      , $e = ["Top", "Right", "Bottom", "Left"]
      , De = function(t, e) {
        return t = e || t,
        "none" === ae.css(t, "display") || !ae.contains(t.ownerDocument, t)
    }
      , Ae = ae.access = function(t, e, i, n, a, r, o) {
        var s = 0
          , l = t.length
          , c = null == i;
        if ("object" === ae.type(i)) {
            a = !0;
            for (s in i)
                ae.access(t, e, s, i[s], !0, r, o)
        } else if (void 0 !== n && (a = !0,
        ae.isFunction(n) || (o = !0),
        c && (o ? (e.call(t, n),
        e = null) : (c = e,
        e = function(t, e, i) {
            return c.call(ae(t), i)
        }
        )),
        e))
            for (; l > s; s++)
                e(t[s], i, o ? n : n.call(t[s], s, e(t[s], i)));
        return a ? t : c ? e.call(t) : l ? e(t[0], i) : r
    }
      , Oe = /^(?:checkbox|radio)$/i;
    !function() {
        var t = fe.createElement("input")
          , e = fe.createElement("div")
          , i = fe.createDocumentFragment();
        if (e.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>",
        ie.leadingWhitespace = 3 === e.firstChild.nodeType,
        ie.tbody = !e.getElementsByTagName("tbody").length,
        ie.htmlSerialize = !!e.getElementsByTagName("link").length,
        ie.html5Clone = "<:nav></:nav>" !== fe.createElement("nav").cloneNode(!0).outerHTML,
        t.type = "checkbox",
        t.checked = !0,
        i.appendChild(t),
        ie.appendChecked = t.checked,
        e.innerHTML = "<textarea>x</textarea>",
        ie.noCloneChecked = !!e.cloneNode(!0).lastChild.defaultValue,
        i.appendChild(e),
        e.innerHTML = "<input type='radio' checked='checked' name='t'/>",
        ie.checkClone = e.cloneNode(!0).cloneNode(!0).lastChild.checked,
        ie.noCloneEvent = !0,
        e.attachEvent && (e.attachEvent("onclick", function() {
            ie.noCloneEvent = !1
        }),
        e.cloneNode(!0).click()),
        null == ie.deleteExpando) {
            ie.deleteExpando = !0;
            try {
                delete e.test
            } catch (n) {
                ie.deleteExpando = !1
            }
        }
    }(),
    function() {
        var e, i, n = fe.createElement("div");
        for (e in {
            submit: !0,
            change: !0,
            focusin: !0
        })
            i = "on" + e,
            (ie[e + "Bubbles"] = i in t) || (n.setAttribute(i, "t"),
            ie[e + "Bubbles"] = n.attributes[i].expando === !1);
        n = null
    }();
    var Ee = /^(?:input|select|textarea)$/i
      , Pe = /^key/
      , Ne = /^(?:mouse|pointer|contextmenu)|click/
      , Me = /^(?:focusinfocus|focusoutblur)$/
      , Re = /^([^.]*)(?:\.(.+)|)$/;
    ae.event = {
        global: {},
        add: function(t, e, i, n, a) {
            var r, o, s, l, c, u, d, h, p, f, m, g = ae._data(t);
            if (g) {
                for (i.handler && (l = i,
                i = l.handler,
                a = l.selector),
                i.guid || (i.guid = ae.guid++),
                (o = g.events) || (o = g.events = {}),
                (u = g.handle) || (u = g.handle = function(t) {
                    return typeof ae === Te || t && ae.event.triggered === t.type ? void 0 : ae.event.dispatch.apply(u.elem, arguments)
                }
                ,
                u.elem = t),
                e = (e || "").match(ye) || [""],
                s = e.length; s--; )
                    r = Re.exec(e[s]) || [],
                    p = m = r[1],
                    f = (r[2] || "").split(".").sort(),
                    p && (c = ae.event.special[p] || {},
                    p = (a ? c.delegateType : c.bindType) || p,
                    c = ae.event.special[p] || {},
                    d = ae.extend({
                        type: p,
                        origType: m,
                        data: n,
                        handler: i,
                        guid: i.guid,
                        selector: a,
                        needsContext: a && ae.expr.match.needsContext.test(a),
                        namespace: f.join(".")
                    }, l),
                    (h = o[p]) || (h = o[p] = [],
                    h.delegateCount = 0,
                    c.setup && c.setup.call(t, n, f, u) !== !1 || (t.addEventListener ? t.addEventListener(p, u, !1) : t.attachEvent && t.attachEvent("on" + p, u))),
                    c.add && (c.add.call(t, d),
                    d.handler.guid || (d.handler.guid = i.guid)),
                    a ? h.splice(h.delegateCount++, 0, d) : h.push(d),
                    ae.event.global[p] = !0);
                t = null
            }
        },
        remove: function(t, e, i, n, a) {
            var r, o, s, l, c, u, d, h, p, f, m, g = ae.hasData(t) && ae._data(t);
            if (g && (u = g.events)) {
                for (e = (e || "").match(ye) || [""],
                c = e.length; c--; )
                    if (s = Re.exec(e[c]) || [],
                    p = m = s[1],
                    f = (s[2] || "").split(".").sort(),
                    p) {
                        for (d = ae.event.special[p] || {},
                        p = (n ? d.delegateType : d.bindType) || p,
                        h = u[p] || [],
                        s = s[2] && new RegExp("(^|\\.)" + f.join("\\.(?:.*\\.|)") + "(\\.|$)"),
                        l = r = h.length; r--; )
                            o = h[r],
                            !a && m !== o.origType || i && i.guid !== o.guid || s && !s.test(o.namespace) || n && n !== o.selector && ("**" !== n || !o.selector) || (h.splice(r, 1),
                            o.selector && h.delegateCount--,
                            d.remove && d.remove.call(t, o));
                        l && !h.length && (d.teardown && d.teardown.call(t, f, g.handle) !== !1 || ae.removeEvent(t, p, g.handle),
                        delete u[p])
                    } else
                        for (p in u)
                            ae.event.remove(t, p + e[c], i, n, !0);
                ae.isEmptyObject(u) && (delete g.handle,
                ae._removeData(t, "events"))
            }
        },
        trigger: function(e, i, n, a) {
            var r, o, s, l, c, u, d, h = [n || fe], p = ee.call(e, "type") ? e.type : e, f = ee.call(e, "namespace") ? e.namespace.split(".") : [];
            if (s = u = n = n || fe,
            3 !== n.nodeType && 8 !== n.nodeType && !Me.test(p + ae.event.triggered) && (p.indexOf(".") >= 0 && (f = p.split("."),
            p = f.shift(),
            f.sort()),
            o = p.indexOf(":") < 0 && "on" + p,
            e = e[ae.expando] ? e : new ae.Event(p,"object" == typeof e && e),
            e.isTrigger = a ? 2 : 3,
            e.namespace = f.join("."),
            e.namespace_re = e.namespace ? new RegExp("(^|\\.)" + f.join("\\.(?:.*\\.|)") + "(\\.|$)") : null,
            e.result = void 0,
            e.target || (e.target = n),
            i = null == i ? [e] : ae.makeArray(i, [e]),
            c = ae.event.special[p] || {},
            a || !c.trigger || c.trigger.apply(n, i) !== !1)) {
                if (!a && !c.noBubble && !ae.isWindow(n)) {
                    for (l = c.delegateType || p,
                    Me.test(l + p) || (s = s.parentNode); s; s = s.parentNode)
                        h.push(s),
                        u = s;
                    u === (n.ownerDocument || fe) && h.push(u.defaultView || u.parentWindow || t)
                }
                for (d = 0; (s = h[d++]) && !e.isPropagationStopped(); )
                    e.type = d > 1 ? l : c.bindType || p,
                    r = (ae._data(s, "events") || {})[e.type] && ae._data(s, "handle"),
                    r && r.apply(s, i),
                    r = o && s[o],
                    r && r.apply && ae.acceptData(s) && (e.result = r.apply(s, i),
                    e.result === !1 && e.preventDefault());
                if (e.type = p,
                !a && !e.isDefaultPrevented() && (!c._default || c._default.apply(h.pop(), i) === !1) && ae.acceptData(n) && o && n[p] && !ae.isWindow(n)) {
                    u = n[o],
                    u && (n[o] = null),
                    ae.event.triggered = p;
                    try {
                        n[p]()
                    } catch (m) {}
                    ae.event.triggered = void 0,
                    u && (n[o] = u)
                }
                return e.result
            }
        },
        dispatch: function(t) {
            t = ae.event.fix(t);
            var e, i, n, a, r, o = [], s = G.call(arguments), l = (ae._data(this, "events") || {})[t.type] || [], c = ae.event.special[t.type] || {};
            if (s[0] = t,
            t.delegateTarget = this,
            !c.preDispatch || c.preDispatch.call(this, t) !== !1) {
                for (o = ae.event.handlers.call(this, t, l),
                e = 0; (a = o[e++]) && !t.isPropagationStopped(); )
                    for (t.currentTarget = a.elem,
                    r = 0; (n = a.handlers[r++]) && !t.isImmediatePropagationStopped(); )
                        (!t.namespace_re || t.namespace_re.test(n.namespace)) && (t.handleObj = n,
                        t.data = n.data,
                        i = ((ae.event.special[n.origType] || {}).handle || n.handler).apply(a.elem, s),
                        void 0 !== i && (t.result = i) === !1 && (t.preventDefault(),
                        t.stopPropagation()));
                return c.postDispatch && c.postDispatch.call(this, t),
                t.result
            }
        },
        handlers: function(t, e) {
            var i, n, a, r, o = [], s = e.delegateCount, l = t.target;
            if (s && l.nodeType && (!t.button || "click" !== t.type))
                for (; l != this; l = l.parentNode || this)
                    if (1 === l.nodeType && (l.disabled !== !0 || "click" !== t.type)) {
                        for (a = [],
                        r = 0; s > r; r++)
                            n = e[r],
                            i = n.selector + " ",
                            void 0 === a[i] && (a[i] = n.needsContext ? ae(i, this).index(l) >= 0 : ae.find(i, this, null, [l]).length),
                            a[i] && a.push(n);
                        a.length && o.push({
                            elem: l,
                            handlers: a
                        })
                    }
            return s < e.length && o.push({
                elem: this,
                handlers: e.slice(s)
            }),
            o
        },
        fix: function(t) {
            if (t[ae.expando])
                return t;
            var e, i, n, a = t.type, r = t, o = this.fixHooks[a];
            for (o || (this.fixHooks[a] = o = Ne.test(a) ? this.mouseHooks : Pe.test(a) ? this.keyHooks : {}),
            n = o.props ? this.props.concat(o.props) : this.props,
            t = new ae.Event(r),
            e = n.length; e--; )
                i = n[e],
                t[i] = r[i];
            return t.target || (t.target = r.srcElement || fe),
            3 === t.target.nodeType && (t.target = t.target.parentNode),
            t.metaKey = !!t.metaKey,
            o.filter ? o.filter(t, r) : t
        },
        props: "altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),
        fixHooks: {},
        keyHooks: {
            props: "char charCode key keyCode".split(" "),
            filter: function(t, e) {
                return null == t.which && (t.which = null != e.charCode ? e.charCode : e.keyCode),
                t
            }
        },
        mouseHooks: {
            props: "button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),
            filter: function(t, e) {
                var i, n, a, r = e.button, o = e.fromElement;
                return null == t.pageX && null != e.clientX && (n = t.target.ownerDocument || fe,
                a = n.documentElement,
                i = n.body,
                t.pageX = e.clientX + (a && a.scrollLeft || i && i.scrollLeft || 0) - (a && a.clientLeft || i && i.clientLeft || 0),
                t.pageY = e.clientY + (a && a.scrollTop || i && i.scrollTop || 0) - (a && a.clientTop || i && i.clientTop || 0)),
                !t.relatedTarget && o && (t.relatedTarget = o === t.target ? e.toElement : o),
                t.which || void 0 === r || (t.which = 1 & r ? 1 : 2 & r ? 3 : 4 & r ? 2 : 0),
                t
            }
        },
        special: {
            load: {
                noBubble: !0
            },
            focus: {
                trigger: function() {
                    if (this !== f() && this.focus)
                        try {
                            return this.focus(),
                            !1
                        } catch (t) {}
                },
                delegateType: "focusin"
            },
            blur: {
                trigger: function() {
                    return this === f() && this.blur ? (this.blur(),
                    !1) : void 0
                },
                delegateType: "focusout"
            },
            click: {
                trigger: function() {
                    return ae.nodeName(this, "input") && "checkbox" === this.type && this.click ? (this.click(),
                    !1) : void 0
                },
                _default: function(t) {
                    return ae.nodeName(t.target, "a")
                }
            },
            beforeunload: {
                postDispatch: function(t) {
                    void 0 !== t.result && t.originalEvent && (t.originalEvent.returnValue = t.result)
                }
            }
        },
        simulate: function(t, e, i, n) {
            var a = ae.extend(new ae.Event, i, {
                type: t,
                isSimulated: !0,
                originalEvent: {}
            });
            n ? ae.event.trigger(a, null, e) : ae.event.dispatch.call(e, a),
            a.isDefaultPrevented() && i.preventDefault()
        }
    },
    ae.removeEvent = fe.removeEventListener ? function(t, e, i) {
        t.removeEventListener && t.removeEventListener(e, i, !1)
    }
    : function(t, e, i) {
        var n = "on" + e;
        t.detachEvent && (typeof t[n] === Te && (t[n] = null),
        t.detachEvent(n, i))
    }
    ,
    ae.Event = function(t, e) {
        return this instanceof ae.Event ? (t && t.type ? (this.originalEvent = t,
        this.type = t.type,
        this.isDefaultPrevented = t.defaultPrevented || void 0 === t.defaultPrevented && t.returnValue === !1 ? h : p) : this.type = t,
        e && ae.extend(this, e),
        this.timeStamp = t && t.timeStamp || ae.now(),
        void (this[ae.expando] = !0)) : new ae.Event(t,e)
    }
    ,
    ae.Event.prototype = {
        isDefaultPrevented: p,
        isPropagationStopped: p,
        isImmediatePropagationStopped: p,
        preventDefault: function() {
            var t = this.originalEvent;
            this.isDefaultPrevented = h,
            t && (t.preventDefault ? t.preventDefault() : t.returnValue = !1)
        },
        stopPropagation: function() {
            var t = this.originalEvent;
            this.isPropagationStopped = h,
            t && (t.stopPropagation && t.stopPropagation(),
            t.cancelBubble = !0)
        },
        stopImmediatePropagation: function() {
            var t = this.originalEvent;
            this.isImmediatePropagationStopped = h,
            t && t.stopImmediatePropagation && t.stopImmediatePropagation(),
            this.stopPropagation()
        }
    },
    ae.each({
        mouseenter: "mouseover",
        mouseleave: "mouseout",
        pointerenter: "pointerover",
        pointerleave: "pointerout"
    }, function(t, e) {
        ae.event.special[t] = {
            delegateType: e,
            bindType: e,
            handle: function(t) {
                var i, n = this, a = t.relatedTarget, r = t.handleObj;
                return (!a || a !== n && !ae.contains(n, a)) && (t.type = r.origType,
                i = r.handler.apply(this, arguments),
                t.type = e),
                i
            }
        }
    }),
    ie.submitBubbles || (ae.event.special.submit = {
        setup: function() {
            return ae.nodeName(this, "form") ? !1 : void ae.event.add(this, "click._submit keypress._submit", function(t) {
                var e = t.target
                  , i = ae.nodeName(e, "input") || ae.nodeName(e, "button") ? e.form : void 0;
                i && !ae._data(i, "submitBubbles") && (ae.event.add(i, "submit._submit", function(t) {
                    t._submit_bubble = !0
                }),
                ae._data(i, "submitBubbles", !0))
            })
        },
        postDispatch: function(t) {
            t._submit_bubble && (delete t._submit_bubble,
            this.parentNode && !t.isTrigger && ae.event.simulate("submit", this.parentNode, t, !0))
        },
        teardown: function() {
            return ae.nodeName(this, "form") ? !1 : void ae.event.remove(this, "._submit")
        }
    }),
    ie.changeBubbles || (ae.event.special.change = {
        setup: function() {
            return Ee.test(this.nodeName) ? (("checkbox" === this.type || "radio" === this.type) && (ae.event.add(this, "propertychange._change", function(t) {
                "checked" === t.originalEvent.propertyName && (this._just_changed = !0)
            }),
            ae.event.add(this, "click._change", function(t) {
                this._just_changed && !t.isTrigger && (this._just_changed = !1),
                ae.event.simulate("change", this, t, !0)
            })),
            !1) : void ae.event.add(this, "beforeactivate._change", function(t) {
                var e = t.target;
                Ee.test(e.nodeName) && !ae._data(e, "changeBubbles") && (ae.event.add(e, "change._change", function(t) {
                    !this.parentNode || t.isSimulated || t.isTrigger || ae.event.simulate("change", this.parentNode, t, !0)
                }),
                ae._data(e, "changeBubbles", !0))
            })
        },
        handle: function(t) {
            var e = t.target;
            return this !== e || t.isSimulated || t.isTrigger || "radio" !== e.type && "checkbox" !== e.type ? t.handleObj.handler.apply(this, arguments) : void 0
        },
        teardown: function() {
            return ae.event.remove(this, "._change"),
            !Ee.test(this.nodeName)
        }
    }),
    ie.focusinBubbles || ae.each({
        focus: "focusin",
        blur: "focusout"
    }, function(t, e) {
        var i = function(t) {
            ae.event.simulate(e, t.target, ae.event.fix(t), !0)
        };
        ae.event.special[e] = {
            setup: function() {
                var n = this.ownerDocument || this
                  , a = ae._data(n, e);
                a || n.addEventListener(t, i, !0),
                ae._data(n, e, (a || 0) + 1)
            },
            teardown: function() {
                var n = this.ownerDocument || this
                  , a = ae._data(n, e) - 1;
                a ? ae._data(n, e, a) : (n.removeEventListener(t, i, !0),
                ae._removeData(n, e))
            }
        }
    }),
    ae.fn.extend({
        on: function(t, e, i, n, a) {
            var r, o;
            if ("object" == typeof t) {
                "string" != typeof e && (i = i || e,
                e = void 0);
                for (r in t)
                    this.on(r, e, i, t[r], a);
                return this
            }
            if (null == i && null == n ? (n = e,
            i = e = void 0) : null == n && ("string" == typeof e ? (n = i,
            i = void 0) : (n = i,
            i = e,
            e = void 0)),
            n === !1)
                n = p;
            else if (!n)
                return this;
            return 1 === a && (o = n,
            n = function(t) {
                return ae().off(t),
                o.apply(this, arguments)
            }
            ,
            n.guid = o.guid || (o.guid = ae.guid++)),
            this.each(function() {
                ae.event.add(this, t, n, i, e)
            })
        },
        one: function(t, e, i, n) {
            return this.on(t, e, i, n, 1)
        },
        off: function(t, e, i) {
            var n, a;
            if (t && t.preventDefault && t.handleObj)
                return n = t.handleObj,
                ae(t.delegateTarget).off(n.namespace ? n.origType + "." + n.namespace : n.origType, n.selector, n.handler),
                this;
            if ("object" == typeof t) {
                for (a in t)
                    this.off(a, e, t[a]);
                return this
            }
            return (e === !1 || "function" == typeof e) && (i = e,
            e = void 0),
            i === !1 && (i = p),
            this.each(function() {
                ae.event.remove(this, t, i, e)
            })
        },
        trigger: function(t, e) {
            return this.each(function() {
                ae.event.trigger(t, e, this)
            })
        },
        triggerHandler: function(t, e) {
            var i = this[0];
            return i ? ae.event.trigger(t, e, i, !0) : void 0
        }
    });
    var Ie = "abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video"
      , Be = / jQuery\d+="(?:null|\d+)"/g
      , Le = new RegExp("<(?:" + Ie + ")[\\s/>]","i")
      , je = /^\s+/
      , Fe = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi
      , Ue = /<([\w:]+)/
      , ze = /<tbody/i
      , He = /<|&#?\w+;/
      , We = /<(?:script|style|link)/i
      , qe = /checked\s*(?:[^=]|=\s*.checked.)/i
      , Ye = /^$|\/(?:java|ecma)script/i
      , Xe = /^true\/(.*)/
      , Ve = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g
      , Ge = {
        option: [1, "<select multiple='multiple'>", "</select>"],
        legend: [1, "<fieldset>", "</fieldset>"],
        area: [1, "<map>", "</map>"],
        param: [1, "<object>", "</object>"],
        thead: [1, "<table>", "</table>"],
        tr: [2, "<table><tbody>", "</tbody></table>"],
        col: [2, "<table><tbody></tbody><colgroup>", "</colgroup></table>"],
        td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
        _default: ie.htmlSerialize ? [0, "", ""] : [1, "X<div>", "</div>"]
    }
      , Qe = m(fe)
      , Je = Qe.appendChild(fe.createElement("div"));
    Ge.optgroup = Ge.option,
    Ge.tbody = Ge.tfoot = Ge.colgroup = Ge.caption = Ge.thead,
    Ge.th = Ge.td,
    ae.extend({
        clone: function(t, e, i) {
            var n, a, r, o, s, l = ae.contains(t.ownerDocument, t);
            if (ie.html5Clone || ae.isXMLDoc(t) || !Le.test("<" + t.nodeName + ">") ? r = t.cloneNode(!0) : (Je.innerHTML = t.outerHTML,
            Je.removeChild(r = Je.firstChild)),
            !(ie.noCloneEvent && ie.noCloneChecked || 1 !== t.nodeType && 11 !== t.nodeType || ae.isXMLDoc(t)))
                for (n = g(r),
                s = g(t),
                o = 0; null != (a = s[o]); ++o)
                    n[o] && T(a, n[o]);
            if (e)
                if (i)
                    for (s = s || g(t),
                    n = n || g(r),
                    o = 0; null != (a = s[o]); o++)
                        w(a, n[o]);
                else
                    w(t, r);
            return n = g(r, "script"),
            n.length > 0 && x(n, !l && g(t, "script")),
            n = s = a = null,
            r
        },
        buildFragment: function(t, e, i, n) {
            for (var a, r, o, s, l, c, u, d = t.length, h = m(e), p = [], f = 0; d > f; f++)
                if (r = t[f],
                r || 0 === r)
                    if ("object" === ae.type(r))
                        ae.merge(p, r.nodeType ? [r] : r);
                    else if (He.test(r)) {
                        for (s = s || h.appendChild(e.createElement("div")),
                        l = (Ue.exec(r) || ["", ""])[1].toLowerCase(),
                        u = Ge[l] || Ge._default,
                        s.innerHTML = u[1] + r.replace(Fe, "<$1></$2>") + u[2],
                        a = u[0]; a--; )
                            s = s.lastChild;
                        if (!ie.leadingWhitespace && je.test(r) && p.push(e.createTextNode(je.exec(r)[0])),
                        !ie.tbody)
                            for (r = "table" !== l || ze.test(r) ? "<table>" !== u[1] || ze.test(r) ? 0 : s : s.firstChild,
                            a = r && r.childNodes.length; a--; )
                                ae.nodeName(c = r.childNodes[a], "tbody") && !c.childNodes.length && r.removeChild(c);
                        for (ae.merge(p, s.childNodes),
                        s.textContent = ""; s.firstChild; )
                            s.removeChild(s.firstChild);
                        s = h.lastChild
                    } else
                        p.push(e.createTextNode(r));
            for (s && h.removeChild(s),
            ie.appendChecked || ae.grep(g(p, "input"), v),
            f = 0; r = p[f++]; )
                if ((!n || -1 === ae.inArray(r, n)) && (o = ae.contains(r.ownerDocument, r),
                s = g(h.appendChild(r), "script"),
                o && x(s),
                i))
                    for (a = 0; r = s[a++]; )
                        Ye.test(r.type || "") && i.push(r);
            return s = null,
            h
        },
        cleanData: function(t, e) {
            for (var i, n, a, r, o = 0, s = ae.expando, l = ae.cache, c = ie.deleteExpando, u = ae.event.special; null != (i = t[o]); o++)
                if ((e || ae.acceptData(i)) && (a = i[s],
                r = a && l[a])) {
                    if (r.events)
                        for (n in r.events)
                            u[n] ? ae.event.remove(i, n) : ae.removeEvent(i, n, r.handle);
                    l[a] && (delete l[a],
                    c ? delete i[s] : typeof i.removeAttribute !== Te ? i.removeAttribute(s) : i[s] = null,
                    V.push(a))
                }
        }
    }),
    ae.fn.extend({
        text: function(t) {
            return Ae(this, function(t) {
                return void 0 === t ? ae.text(this) : this.empty().append((this[0] && this[0].ownerDocument || fe).createTextNode(t))
            }, null, t, arguments.length)
        },
        append: function() {
            return this.domManip(arguments, function(t) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var e = _(this, t);
                    e.appendChild(t)
                }
            })
        },
        prepend: function() {
            return this.domManip(arguments, function(t) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var e = _(this, t);
                    e.insertBefore(t, e.firstChild)
                }
            })
        },
        before: function() {
            return this.domManip(arguments, function(t) {
                this.parentNode && this.parentNode.insertBefore(t, this)
            })
        },
        after: function() {
            return this.domManip(arguments, function(t) {
                this.parentNode && this.parentNode.insertBefore(t, this.nextSibling)
            })
        },
        remove: function(t, e) {
            for (var i, n = t ? ae.filter(t, this) : this, a = 0; null != (i = n[a]); a++)
                e || 1 !== i.nodeType || ae.cleanData(g(i)),
                i.parentNode && (e && ae.contains(i.ownerDocument, i) && x(g(i, "script")),
                i.parentNode.removeChild(i));
            return this
        },
        empty: function() {
            for (var t, e = 0; null != (t = this[e]); e++) {
                for (1 === t.nodeType && ae.cleanData(g(t, !1)); t.firstChild; )
                    t.removeChild(t.firstChild);
                t.options && ae.nodeName(t, "select") && (t.options.length = 0)
            }
            return this
        },
        clone: function(t, e) {
            return t = null == t ? !1 : t,
            e = null == e ? t : e,
            this.map(function() {
                return ae.clone(this, t, e)
            })
        },
        html: function(t) {
            return Ae(this, function(t) {
                var e = this[0] || {}
                  , i = 0
                  , n = this.length;
                if (void 0 === t)
                    return 1 === e.nodeType ? e.innerHTML.replace(Be, "") : void 0;
                if (!("string" != typeof t || We.test(t) || !ie.htmlSerialize && Le.test(t) || !ie.leadingWhitespace && je.test(t) || Ge[(Ue.exec(t) || ["", ""])[1].toLowerCase()])) {
                    t = t.replace(Fe, "<$1></$2>");
                    try {
                        for (; n > i; i++)
                            e = this[i] || {},
                            1 === e.nodeType && (ae.cleanData(g(e, !1)),
                            e.innerHTML = t);
                        e = 0
                    } catch (a) {}
                }
                e && this.empty().append(t)
            }, null, t, arguments.length)
        },
        replaceWith: function() {
            var t = arguments[0];
            return this.domManip(arguments, function(e) {
                t = this.parentNode,
                ae.cleanData(g(this)),
                t && t.replaceChild(e, this)
            }),
            t && (t.length || t.nodeType) ? this : this.remove()
        },
        detach: function(t) {
            return this.remove(t, !0)
        },
        domManip: function(t, e) {
            t = Q.apply([], t);
            var i, n, a, r, o, s, l = 0, c = this.length, u = this, d = c - 1, h = t[0], p = ae.isFunction(h);
            if (p || c > 1 && "string" == typeof h && !ie.checkClone && qe.test(h))
                return this.each(function(i) {
                    var n = u.eq(i);
                    p && (t[0] = h.call(this, i, n.html())),
                    n.domManip(t, e)
                });
            if (c && (s = ae.buildFragment(t, this[0].ownerDocument, !1, this),
            i = s.firstChild,
            1 === s.childNodes.length && (s = i),
            i)) {
                for (r = ae.map(g(s, "script"), y),
                a = r.length; c > l; l++)
                    n = s,
                    l !== d && (n = ae.clone(n, !0, !0),
                    a && ae.merge(r, g(n, "script"))),
                    e.call(this[l], n, l);
                if (a)
                    for (o = r[r.length - 1].ownerDocument,
                    ae.map(r, b),
                    l = 0; a > l; l++)
                        n = r[l],
                        Ye.test(n.type || "") && !ae._data(n, "globalEval") && ae.contains(o, n) && (n.src ? ae._evalUrl && ae._evalUrl(n.src) : ae.globalEval((n.text || n.textContent || n.innerHTML || "").replace(Ve, "")));
                s = i = null
            }
            return this
        }
    }),
    ae.each({
        appendTo: "append",
        prependTo: "prepend",
        insertBefore: "before",
        insertAfter: "after",
        replaceAll: "replaceWith"
    }, function(t, e) {
        ae.fn[t] = function(t) {
            for (var i, n = 0, a = [], r = ae(t), o = r.length - 1; o >= n; n++)
                i = n === o ? this : this.clone(!0),
                ae(r[n])[e](i),
                J.apply(a, i.get());
            return this.pushStack(a)
        }
    });
    var Ke, Ze = {};
    !function() {
        var t;
        ie.shrinkWrapBlocks = function() {
            if (null != t)
                return t;
            t = !1;
            var e, i, n;
            return i = fe.getElementsByTagName("body")[0],
            i && i.style ? (e = fe.createElement("div"),
            n = fe.createElement("div"),
            n.style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px",
            i.appendChild(n).appendChild(e),
            typeof e.style.zoom !== Te && (e.style.cssText = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:1px;width:1px;zoom:1",
            e.appendChild(fe.createElement("div")).style.width = "5px",
            t = 3 !== e.offsetWidth),
            i.removeChild(n),
            t) : void 0
        }
    }();
    var ti, ei, ii = /^margin/, ni = new RegExp("^(" + ke + ")(?!px)[a-z%]+$","i"), ai = /^(top|right|bottom|left)$/;
    t.getComputedStyle ? (ti = function(e) {
        return e.ownerDocument.defaultView.opener ? e.ownerDocument.defaultView.getComputedStyle(e, null) : t.getComputedStyle(e, null)
    }
    ,
    ei = function(t, e, i) {
        var n, a, r, o, s = t.style;
        return i = i || ti(t),
        o = i ? i.getPropertyValue(e) || i[e] : void 0,
        i && ("" !== o || ae.contains(t.ownerDocument, t) || (o = ae.style(t, e)),
        ni.test(o) && ii.test(e) && (n = s.width,
        a = s.minWidth,
        r = s.maxWidth,
        s.minWidth = s.maxWidth = s.width = o,
        o = i.width,
        s.width = n,
        s.minWidth = a,
        s.maxWidth = r)),
        void 0 === o ? o : o + ""
    }
    ) : fe.documentElement.currentStyle && (ti = function(t) {
        return t.currentStyle
    }
    ,
    ei = function(t, e, i) {
        var n, a, r, o, s = t.style;
        return i = i || ti(t),
        o = i ? i[e] : void 0,
        null == o && s && s[e] && (o = s[e]),
        ni.test(o) && !ai.test(e) && (n = s.left,
        a = t.runtimeStyle,
        r = a && a.left,
        r && (a.left = t.currentStyle.left),
        s.left = "fontSize" === e ? "1em" : o,
        o = s.pixelLeft + "px",
        s.left = n,
        r && (a.left = r)),
        void 0 === o ? o : o + "" || "auto"
    }
    ),
    !function() {
        function e() {
            var e, i, n, a;
            i = fe.getElementsByTagName("body")[0],
            i && i.style && (e = fe.createElement("div"),
            n = fe.createElement("div"),
            n.style.cssText = "position:absolute;border:0;width:0;height:0;top:0;left:-9999px",
            i.appendChild(n).appendChild(e),
            e.style.cssText = "-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;display:block;margin-top:1%;top:1%;border:1px;padding:1px;width:4px;position:absolute",
            r = o = !1,
            l = !0,
            t.getComputedStyle && (r = "1%" !== (t.getComputedStyle(e, null) || {}).top,
            o = "4px" === (t.getComputedStyle(e, null) || {
                width: "4px"
            }).width,
            a = e.appendChild(fe.createElement("div")),
            a.style.cssText = e.style.cssText = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:0",
            a.style.marginRight = a.style.width = "0",
            e.style.width = "1px",
            l = !parseFloat((t.getComputedStyle(a, null) || {}).marginRight),
            e.removeChild(a)),
            e.innerHTML = "<table><tr><td></td><td>t</td></tr></table>",
            a = e.getElementsByTagName("td"),
            a[0].style.cssText = "margin:0;border:0;padding:0;display:none",
            s = 0 === a[0].offsetHeight,
            s && (a[0].style.display = "",
            a[1].style.display = "none",
            s = 0 === a[0].offsetHeight),
            i.removeChild(n))
        }
        var i, n, a, r, o, s, l;
        i = fe.createElement("div"),
        i.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>",
        a = i.getElementsByTagName("a")[0],
        (n = a && a.style) && (n.cssText = "float:left;opacity:.5",
        ie.opacity = "0.5" === n.opacity,
        ie.cssFloat = !!n.cssFloat,
        i.style.backgroundClip = "content-box",
        i.cloneNode(!0).style.backgroundClip = "",
        ie.clearCloneStyle = "content-box" === i.style.backgroundClip,
        ie.boxSizing = "" === n.boxSizing || "" === n.MozBoxSizing || "" === n.WebkitBoxSizing,
        ae.extend(ie, {
            reliableHiddenOffsets: function() {
                return null == s && e(),
                s
            },
            boxSizingReliable: function() {
                return null == o && e(),
                o
            },
            pixelPosition: function() {
                return null == r && e(),
                r
            },
            reliableMarginRight: function() {
                return null == l && e(),
                l
            }
        }))
    }(),
    ae.swap = function(t, e, i, n) {
        var a, r, o = {};
        for (r in e)
            o[r] = t.style[r],
            t.style[r] = e[r];
        a = i.apply(t, n || []);
        for (r in e)
            t.style[r] = o[r];
        return a
    }
    ;
    var ri = /alpha\([^)]*\)/i
      , oi = /opacity\s*=\s*([^)]*)/
      , si = /^(none|table(?!-c[ea]).+)/
      , li = new RegExp("^(" + ke + ")(.*)$","i")
      , ci = new RegExp("^([+-])=(" + ke + ")","i")
      , ui = {
        position: "absolute",
        visibility: "hidden",
        display: "block"
    }
      , di = {
        letterSpacing: "0",
        fontWeight: "400"
    }
      , hi = ["Webkit", "O", "Moz", "ms"];
    ae.extend({
        cssHooks: {
            opacity: {
                get: function(t, e) {
                    if (e) {
                        var i = ei(t, "opacity");
                        return "" === i ? "1" : i
                    }
                }
            }
        },
        cssNumber: {
            columnCount: !0,
            fillOpacity: !0,
            flexGrow: !0,
            flexShrink: !0,
            fontWeight: !0,
            lineHeight: !0,
            opacity: !0,
            order: !0,
            orphans: !0,
            widows: !0,
            zIndex: !0,
            zoom: !0
        },
        cssProps: {
            "float": ie.cssFloat ? "cssFloat" : "styleFloat"
        },
        style: function(t, e, i, n) {
            if (t && 3 !== t.nodeType && 8 !== t.nodeType && t.style) {
                var a, r, o, s = ae.camelCase(e), l = t.style;
                if (e = ae.cssProps[s] || (ae.cssProps[s] = $(l, s)),
                o = ae.cssHooks[e] || ae.cssHooks[s],
                void 0 === i)
                    return o && "get"in o && void 0 !== (a = o.get(t, !1, n)) ? a : l[e];
                if (r = typeof i,
                "string" === r && (a = ci.exec(i)) && (i = (a[1] + 1) * a[2] + parseFloat(ae.css(t, e)),
                r = "number"),
                null != i && i === i && ("number" !== r || ae.cssNumber[s] || (i += "px"),
                ie.clearCloneStyle || "" !== i || 0 !== e.indexOf("background") || (l[e] = "inherit"),
                !(o && "set"in o && void 0 === (i = o.set(t, i, n)))))
                    try {
                        l[e] = i
                    } catch (c) {}
            }
        },
        css: function(t, e, i, n) {
            var a, r, o, s = ae.camelCase(e);
            return e = ae.cssProps[s] || (ae.cssProps[s] = $(t.style, s)),
            o = ae.cssHooks[e] || ae.cssHooks[s],
            o && "get"in o && (r = o.get(t, !0, i)),
            void 0 === r && (r = ei(t, e, n)),
            "normal" === r && e in di && (r = di[e]),
            "" === i || i ? (a = parseFloat(r),
            i === !0 || ae.isNumeric(a) ? a || 0 : r) : r
        }
    }),
    ae.each(["height", "width"], function(t, e) {
        ae.cssHooks[e] = {
            get: function(t, i, n) {
                return i ? si.test(ae.css(t, "display")) && 0 === t.offsetWidth ? ae.swap(t, ui, function() {
                    return E(t, e, n)
                }) : E(t, e, n) : void 0
            },
            set: function(t, i, n) {
                var a = n && ti(t);
                return A(t, i, n ? O(t, e, n, ie.boxSizing && "border-box" === ae.css(t, "boxSizing", !1, a), a) : 0)
            }
        }
    }),
    ie.opacity || (ae.cssHooks.opacity = {
        get: function(t, e) {
            return oi.test((e && t.currentStyle ? t.currentStyle.filter : t.style.filter) || "") ? .01 * parseFloat(RegExp.$1) + "" : e ? "1" : ""
        },
        set: function(t, e) {
            var i = t.style
              , n = t.currentStyle
              , a = ae.isNumeric(e) ? "alpha(opacity=" + 100 * e + ")" : ""
              , r = n && n.filter || i.filter || "";
            i.zoom = 1,
            (e >= 1 || "" === e) && "" === ae.trim(r.replace(ri, "")) && i.removeAttribute && (i.removeAttribute("filter"),
            "" === e || n && !n.filter) || (i.filter = ri.test(r) ? r.replace(ri, a) : r + " " + a)
        }
    }),
    ae.cssHooks.marginRight = k(ie.reliableMarginRight, function(t, e) {
        return e ? ae.swap(t, {
            display: "inline-block"
        }, ei, [t, "marginRight"]) : void 0
    }),
    ae.each({
        margin: "",
        padding: "",
        border: "Width"
    }, function(t, e) {
        ae.cssHooks[t + e] = {
            expand: function(i) {
                for (var n = 0, a = {}, r = "string" == typeof i ? i.split(" ") : [i]; 4 > n; n++)
                    a[t + $e[n] + e] = r[n] || r[n - 2] || r[0];
                return a
            }
        },
        ii.test(t) || (ae.cssHooks[t + e].set = A)
    }),
    ae.fn.extend({
        css: function(t, e) {
            return Ae(this, function(t, e, i) {
                var n, a, r = {}, o = 0;
                if (ae.isArray(e)) {
                    for (n = ti(t),
                    a = e.length; a > o; o++)
                        r[e[o]] = ae.css(t, e[o], !1, n);
                    return r
                }
                return void 0 !== i ? ae.style(t, e, i) : ae.css(t, e)
            }, t, e, arguments.length > 1)
        },
        show: function() {
            return D(this, !0)
        },
        hide: function() {
            return D(this)
        },
        toggle: function(t) {
            return "boolean" == typeof t ? t ? this.show() : this.hide() : this.each(function() {
                De(this) ? ae(this).show() : ae(this).hide()
            })
        }
    }),
    ae.Tween = P,
    P.prototype = {
        constructor: P,
        init: function(t, e, i, n, a, r) {
            this.elem = t,
            this.prop = i,
            this.easing = a || "swing",
            this.options = e,
            this.start = this.now = this.cur(),
            this.end = n,
            this.unit = r || (ae.cssNumber[i] ? "" : "px")
        },
        cur: function() {
            var t = P.propHooks[this.prop];
            return t && t.get ? t.get(this) : P.propHooks._default.get(this)
        },
        run: function(t) {
            var e, i = P.propHooks[this.prop];
            return this.pos = e = this.options.duration ? ae.easing[this.easing](t, this.options.duration * t, 0, 1, this.options.duration) : t,
            this.now = (this.end - this.start) * e + this.start,
            this.options.step && this.options.step.call(this.elem, this.now, this),
            i && i.set ? i.set(this) : P.propHooks._default.set(this),
            this
        }
    },
    P.prototype.init.prototype = P.prototype,
    P.propHooks = {
        _default: {
            get: function(t) {
                var e;
                return null == t.elem[t.prop] || t.elem.style && null != t.elem.style[t.prop] ? (e = ae.css(t.elem, t.prop, ""),
                e && "auto" !== e ? e : 0) : t.elem[t.prop]
            },
            set: function(t) {
                ae.fx.step[t.prop] ? ae.fx.step[t.prop](t) : t.elem.style && (null != t.elem.style[ae.cssProps[t.prop]] || ae.cssHooks[t.prop]) ? ae.style(t.elem, t.prop, t.now + t.unit) : t.elem[t.prop] = t.now
            }
        }
    },
    P.propHooks.scrollTop = P.propHooks.scrollLeft = {
        set: function(t) {
            t.elem.nodeType && t.elem.parentNode && (t.elem[t.prop] = t.now)
        }
    },
    ae.easing = {
        linear: function(t) {
            return t
        },
        swing: function(t) {
            return .5 - Math.cos(t * Math.PI) / 2
        }
    },
    ae.fx = P.prototype.init,
    ae.fx.step = {};
    var pi, fi, mi = /^(?:toggle|show|hide)$/, gi = new RegExp("^(?:([+-])=|)(" + ke + ")([a-z%]*)$","i"), vi = /queueHooks$/, _i = [I], yi = {
        "*": [function(t, e) {
            var i = this.createTween(t, e)
              , n = i.cur()
              , a = gi.exec(e)
              , r = a && a[3] || (ae.cssNumber[t] ? "" : "px")
              , o = (ae.cssNumber[t] || "px" !== r && +n) && gi.exec(ae.css(i.elem, t))
              , s = 1
              , l = 20;
            if (o && o[3] !== r) {
                r = r || o[3],
                a = a || [],
                o = +n || 1;
                do
                    s = s || ".5",
                    o /= s,
                    ae.style(i.elem, t, o + r);
                while (s !== (s = i.cur() / n) && 1 !== s && --l)
            }
            return a && (o = i.start = +o || +n || 0,
            i.unit = r,
            i.end = a[1] ? o + (a[1] + 1) * a[2] : +a[2]),
            i
        }
        ]
    };
    ae.Animation = ae.extend(L, {
        tweener: function(t, e) {
            ae.isFunction(t) ? (e = t,
            t = ["*"]) : t = t.split(" ");
            for (var i, n = 0, a = t.length; a > n; n++)
                i = t[n],
                yi[i] = yi[i] || [],
                yi[i].unshift(e)
        },
        prefilter: function(t, e) {
            e ? _i.unshift(t) : _i.push(t)
        }
    }),
    ae.speed = function(t, e, i) {
        var n = t && "object" == typeof t ? ae.extend({}, t) : {
            complete: i || !i && e || ae.isFunction(t) && t,
            duration: t,
            easing: i && e || e && !ae.isFunction(e) && e
        };
        return n.duration = ae.fx.off ? 0 : "number" == typeof n.duration ? n.duration : n.duration in ae.fx.speeds ? ae.fx.speeds[n.duration] : ae.fx.speeds._default,
        (null == n.queue || n.queue === !0) && (n.queue = "fx"),
        n.old = n.complete,
        n.complete = function() {
            ae.isFunction(n.old) && n.old.call(this),
            n.queue && ae.dequeue(this, n.queue)
        }
        ,
        n
    }
    ,
    ae.fn.extend({
        fadeTo: function(t, e, i, n) {
            return this.filter(De).css("opacity", 0).show().end().animate({
                opacity: e
            }, t, i, n)
        },
        animate: function(t, e, i, n) {
            var a = ae.isEmptyObject(t)
              , r = ae.speed(e, i, n)
              , o = function() {
                var e = L(this, ae.extend({}, t), r);
                (a || ae._data(this, "finish")) && e.stop(!0)
            };
            return o.finish = o,
            a || r.queue === !1 ? this.each(o) : this.queue(r.queue, o)
        },
        stop: function(t, e, i) {
            var n = function(t) {
                var e = t.stop;
                delete t.stop,
                e(i)
            };
            return "string" != typeof t && (i = e,
            e = t,
            t = void 0),
            e && t !== !1 && this.queue(t || "fx", []),
            this.each(function() {
                var e = !0
                  , a = null != t && t + "queueHooks"
                  , r = ae.timers
                  , o = ae._data(this);
                if (a)
                    o[a] && o[a].stop && n(o[a]);
                else
                    for (a in o)
                        o[a] && o[a].stop && vi.test(a) && n(o[a]);
                for (a = r.length; a--; )
                    r[a].elem !== this || null != t && r[a].queue !== t || (r[a].anim.stop(i),
                    e = !1,
                    r.splice(a, 1));
                (e || !i) && ae.dequeue(this, t)
            })
        },
        finish: function(t) {
            return t !== !1 && (t = t || "fx"),
            this.each(function() {
                var e, i = ae._data(this), n = i[t + "queue"], a = i[t + "queueHooks"], r = ae.timers, o = n ? n.length : 0;
                for (i.finish = !0,
                ae.queue(this, t, []),
                a && a.stop && a.stop.call(this, !0),
                e = r.length; e--; )
                    r[e].elem === this && r[e].queue === t && (r[e].anim.stop(!0),
                    r.splice(e, 1));
                for (e = 0; o > e; e++)
                    n[e] && n[e].finish && n[e].finish.call(this);
                delete i.finish
            })
        }
    }),
    ae.each(["toggle", "show", "hide"], function(t, e) {
        var i = ae.fn[e];
        ae.fn[e] = function(t, n, a) {
            return null == t || "boolean" == typeof t ? i.apply(this, arguments) : this.animate(M(e, !0), t, n, a)
        }
    }),
    ae.each({
        slideDown: M("show"),
        slideUp: M("hide"),
        slideToggle: M("toggle"),
        fadeIn: {
            opacity: "show"
        },
        fadeOut: {
            opacity: "hide"
        },
        fadeToggle: {
            opacity: "toggle"
        }
    }, function(t, e) {
        ae.fn[t] = function(t, i, n) {
            return this.animate(e, t, i, n)
        }
    }),
    ae.timers = [],
    ae.fx.tick = function() {
        var t, e = ae.timers, i = 0;
        for (pi = ae.now(); i < e.length; i++)
            t = e[i],
            t() || e[i] !== t || e.splice(i--, 1);
        e.length || ae.fx.stop(),
        pi = void 0
    }
    ,
    ae.fx.timer = function(t) {
        ae.timers.push(t),
        t() ? ae.fx.start() : ae.timers.pop()
    }
    ,
    ae.fx.interval = 13,
    ae.fx.start = function() {
        fi || (fi = setInterval(ae.fx.tick, ae.fx.interval))
    }
    ,
    ae.fx.stop = function() {
        clearInterval(fi),
        fi = null
    }
    ,
    ae.fx.speeds = {
        slow: 600,
        fast: 200,
        _default: 400
    },
    ae.fn.delay = function(t, e) {
        return t = ae.fx ? ae.fx.speeds[t] || t : t,
        e = e || "fx",
        this.queue(e, function(e, i) {
            var n = setTimeout(e, t);
            i.stop = function() {
                clearTimeout(n)
            }
        })
    }
    ,
    function() {
        var t, e, i, n, a;
        e = fe.createElement("div"),
        e.setAttribute("className", "t"),
        e.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>",
        n = e.getElementsByTagName("a")[0],
        i = fe.createElement("select"),
        a = i.appendChild(fe.createElement("option")),
        t = e.getElementsByTagName("input")[0],
        n.style.cssText = "top:1px",
        ie.getSetAttribute = "t" !== e.className,
        ie.style = /top/.test(n.getAttribute("style")),
        ie.hrefNormalized = "/a" === n.getAttribute("href"),
        ie.checkOn = !!t.value,
        ie.optSelected = a.selected,
        ie.enctype = !!fe.createElement("form").enctype,
        i.disabled = !0,
        ie.optDisabled = !a.disabled,
        t = fe.createElement("input"),
        t.setAttribute("value", ""),
        ie.input = "" === t.getAttribute("value"),
        t.value = "t",
        t.setAttribute("type", "radio"),
        ie.radioValue = "t" === t.value
    }();
    var bi = /\r/g;
    ae.fn.extend({
        val: function(t) {
            var e, i, n, a = this[0];
            return arguments.length ? (n = ae.isFunction(t),
            this.each(function(i) {
                var a;
                1 === this.nodeType && (a = n ? t.call(this, i, ae(this).val()) : t,
                null == a ? a = "" : "number" == typeof a ? a += "" : ae.isArray(a) && (a = ae.map(a, function(t) {
                    return null == t ? "" : t + ""
                })),
                e = ae.valHooks[this.type] || ae.valHooks[this.nodeName.toLowerCase()],
                e && "set"in e && void 0 !== e.set(this, a, "value") || (this.value = a))
            })) : a ? (e = ae.valHooks[a.type] || ae.valHooks[a.nodeName.toLowerCase()],
            e && "get"in e && void 0 !== (i = e.get(a, "value")) ? i : (i = a.value,
            "string" == typeof i ? i.replace(bi, "") : null == i ? "" : i)) : void 0
        }
    }),
    ae.extend({
        valHooks: {
            option: {
                get: function(t) {
                    var e = ae.find.attr(t, "value");
                    return null != e ? e : ae.trim(ae.text(t))
                }
            },
            select: {
                get: function(t) {
                    for (var e, i, n = t.options, a = t.selectedIndex, r = "select-one" === t.type || 0 > a, o = r ? null : [], s = r ? a + 1 : n.length, l = 0 > a ? s : r ? a : 0; s > l; l++)
                        if (i = n[l],
                        !(!i.selected && l !== a || (ie.optDisabled ? i.disabled : null !== i.getAttribute("disabled")) || i.parentNode.disabled && ae.nodeName(i.parentNode, "optgroup"))) {
                            if (e = ae(i).val(),
                            r)
                                return e;
                            o.push(e)
                        }
                    return o
                },
                set: function(t, e) {
                    for (var i, n, a = t.options, r = ae.makeArray(e), o = a.length; o--; )
                        if (n = a[o],
                        ae.inArray(ae.valHooks.option.get(n), r) >= 0)
                            try {
                                n.selected = i = !0
                            } catch (s) {
                                n.scrollHeight
                            }
                        else
                            n.selected = !1;
                    return i || (t.selectedIndex = -1),
                    a
                }
            }
        }
    }),
    ae.each(["radio", "checkbox"], function() {
        ae.valHooks[this] = {
            set: function(t, e) {
                return ae.isArray(e) ? t.checked = ae.inArray(ae(t).val(), e) >= 0 : void 0
            }
        },
        ie.checkOn || (ae.valHooks[this].get = function(t) {
            return null === t.getAttribute("value") ? "on" : t.value
        }
        )
    });
    var xi, wi, Ti = ae.expr.attrHandle, Ci = /^(?:checked|selected)$/i, Si = ie.getSetAttribute, ki = ie.input;
    ae.fn.extend({
        attr: function(t, e) {
            return Ae(this, ae.attr, t, e, arguments.length > 1)
        },
        removeAttr: function(t) {
            return this.each(function() {
                ae.removeAttr(this, t)
            })
        }
    }),
    ae.extend({
        attr: function(t, e, i) {
            var n, a, r = t.nodeType;
            return t && 3 !== r && 8 !== r && 2 !== r ? typeof t.getAttribute === Te ? ae.prop(t, e, i) : (1 === r && ae.isXMLDoc(t) || (e = e.toLowerCase(),
            n = ae.attrHooks[e] || (ae.expr.match.bool.test(e) ? wi : xi)),
            void 0 === i ? n && "get"in n && null !== (a = n.get(t, e)) ? a : (a = ae.find.attr(t, e),
            null == a ? void 0 : a) : null !== i ? n && "set"in n && void 0 !== (a = n.set(t, i, e)) ? a : (t.setAttribute(e, i + ""),
            i) : void ae.removeAttr(t, e)) : void 0
        },
        removeAttr: function(t, e) {
            var i, n, a = 0, r = e && e.match(ye);
            if (r && 1 === t.nodeType)
                for (; i = r[a++]; )
                    n = ae.propFix[i] || i,
                    ae.expr.match.bool.test(i) ? ki && Si || !Ci.test(i) ? t[n] = !1 : t[ae.camelCase("default-" + i)] = t[n] = !1 : ae.attr(t, i, ""),
                    t.removeAttribute(Si ? i : n)
        },
        attrHooks: {
            type: {
                set: function(t, e) {
                    if (!ie.radioValue && "radio" === e && ae.nodeName(t, "input")) {
                        var i = t.value;
                        return t.setAttribute("type", e),
                        i && (t.value = i),
                        e
                    }
                }
            }
        }
    }),
    wi = {
        set: function(t, e, i) {
            return e === !1 ? ae.removeAttr(t, i) : ki && Si || !Ci.test(i) ? t.setAttribute(!Si && ae.propFix[i] || i, i) : t[ae.camelCase("default-" + i)] = t[i] = !0,
            i
        }
    },
    ae.each(ae.expr.match.bool.source.match(/\w+/g), function(t, e) {
        var i = Ti[e] || ae.find.attr;
        Ti[e] = ki && Si || !Ci.test(e) ? function(t, e, n) {
            var a, r;
            return n || (r = Ti[e],
            Ti[e] = a,
            a = null != i(t, e, n) ? e.toLowerCase() : null,
            Ti[e] = r),
            a
        }
        : function(t, e, i) {
            return i ? void 0 : t[ae.camelCase("default-" + e)] ? e.toLowerCase() : null
        }
    }),
    ki && Si || (ae.attrHooks.value = {
        set: function(t, e, i) {
            return ae.nodeName(t, "input") ? void (t.defaultValue = e) : xi && xi.set(t, e, i)
        }
    }),
    Si || (xi = {
        set: function(t, e, i) {
            var n = t.getAttributeNode(i);
            return n || t.setAttributeNode(n = t.ownerDocument.createAttribute(i)),
            n.value = e += "",
            "value" === i || e === t.getAttribute(i) ? e : void 0
        }
    },
    Ti.id = Ti.name = Ti.coords = function(t, e, i) {
        var n;
        return i ? void 0 : (n = t.getAttributeNode(e)) && "" !== n.value ? n.value : null
    }
    ,
    ae.valHooks.button = {
        get: function(t, e) {
            var i = t.getAttributeNode(e);
            return i && i.specified ? i.value : void 0
        },
        set: xi.set
    },
    ae.attrHooks.contenteditable = {
        set: function(t, e, i) {
            xi.set(t, "" === e ? !1 : e, i)
        }
    },
    ae.each(["width", "height"], function(t, e) {
        ae.attrHooks[e] = {
            set: function(t, i) {
                return "" === i ? (t.setAttribute(e, "auto"),
                i) : void 0
            }
        }
    })),
    ie.style || (ae.attrHooks.style = {
        get: function(t) {
            return t.style.cssText || void 0
        },
        set: function(t, e) {
            return t.style.cssText = e + ""
        }
    });
    var $i = /^(?:input|select|textarea|button|object)$/i
      , Di = /^(?:a|area)$/i;
    ae.fn.extend({
        prop: function(t, e) {
            return Ae(this, ae.prop, t, e, arguments.length > 1)
        },
        removeProp: function(t) {
            return t = ae.propFix[t] || t,
            this.each(function() {
                try {
                    this[t] = void 0,
                    delete this[t]
                } catch (e) {}
            })
        }
    }),
    ae.extend({
        propFix: {
            "for": "htmlFor",
            "class": "className"
        },
        prop: function(t, e, i) {
            var n, a, r, o = t.nodeType;
            return t && 3 !== o && 8 !== o && 2 !== o ? (r = 1 !== o || !ae.isXMLDoc(t),
            r && (e = ae.propFix[e] || e,
            a = ae.propHooks[e]),
            void 0 !== i ? a && "set"in a && void 0 !== (n = a.set(t, i, e)) ? n : t[e] = i : a && "get"in a && null !== (n = a.get(t, e)) ? n : t[e]) : void 0
        },
        propHooks: {
            tabIndex: {
                get: function(t) {
                    var e = ae.find.attr(t, "tabindex");
                    return e ? parseInt(e, 10) : $i.test(t.nodeName) || Di.test(t.nodeName) && t.href ? 0 : -1
                }
            }
        }
    }),
    ie.hrefNormalized || ae.each(["href", "src"], function(t, e) {
        ae.propHooks[e] = {
            get: function(t) {
                return t.getAttribute(e, 4)
            }
        }
    }),
    ie.optSelected || (ae.propHooks.selected = {
        get: function(t) {
            var e = t.parentNode;
            return e && (e.selectedIndex,
            e.parentNode && e.parentNode.selectedIndex),
            null
        }
    }),
    ae.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], function() {
        ae.propFix[this.toLowerCase()] = this
    }),
    ie.enctype || (ae.propFix.enctype = "encoding");
    var Ai = /[\t\r\n\f]/g;
    ae.fn.extend({
        addClass: function(t) {
            var e, i, n, a, r, o, s = 0, l = this.length, c = "string" == typeof t && t;
            if (ae.isFunction(t))
                return this.each(function(e) {
                    ae(this).addClass(t.call(this, e, this.className))
                });
            if (c)
                for (e = (t || "").match(ye) || []; l > s; s++)
                    if (i = this[s],
                    n = 1 === i.nodeType && (i.className ? (" " + i.className + " ").replace(Ai, " ") : " ")) {
                        for (r = 0; a = e[r++]; )
                            n.indexOf(" " + a + " ") < 0 && (n += a + " ");
                        o = ae.trim(n),
                        i.className !== o && (i.className = o)
                    }
            return this
        },
        removeClass: function(t) {
            var e, i, n, a, r, o, s = 0, l = this.length, c = 0 === arguments.length || "string" == typeof t && t;
            if (ae.isFunction(t))
                return this.each(function(e) {
                    ae(this).removeClass(t.call(this, e, this.className))
                });
            if (c)
                for (e = (t || "").match(ye) || []; l > s; s++)
                    if (i = this[s],
                    n = 1 === i.nodeType && (i.className ? (" " + i.className + " ").replace(Ai, " ") : "")) {
                        for (r = 0; a = e[r++]; )
                            for (; n.indexOf(" " + a + " ") >= 0; )
                                n = n.replace(" " + a + " ", " ");
                        o = t ? ae.trim(n) : "",
                        i.className !== o && (i.className = o)
                    }
            return this
        },
        toggleClass: function(t, e) {
            var i = typeof t;
            return "boolean" == typeof e && "string" === i ? e ? this.addClass(t) : this.removeClass(t) : this.each(ae.isFunction(t) ? function(i) {
                ae(this).toggleClass(t.call(this, i, this.className, e), e)
            }
            : function() {
                if ("string" === i)
                    for (var e, n = 0, a = ae(this), r = t.match(ye) || []; e = r[n++]; )
                        a.hasClass(e) ? a.removeClass(e) : a.addClass(e);
                else
                    (i === Te || "boolean" === i) && (this.className && ae._data(this, "__className__", this.className),
                    this.className = this.className || t === !1 ? "" : ae._data(this, "__className__") || "")
            }
            )
        },
        hasClass: function(t) {
            for (var e = " " + t + " ", i = 0, n = this.length; n > i; i++)
                if (1 === this[i].nodeType && (" " + this[i].className + " ").replace(Ai, " ").indexOf(e) >= 0)
                    return !0;
            return !1
        }
    }),
    ae.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "), function(t, e) {
        ae.fn[e] = function(t, i) {
            return arguments.length > 0 ? this.on(e, null, t, i) : this.trigger(e)
        }
    }),
    ae.fn.extend({
        hover: function(t, e) {
            return this.mouseenter(t).mouseleave(e || t)
        },
        bind: function(t, e, i) {
            return this.on(t, null, e, i)
        },
        unbind: function(t, e) {
            return this.off(t, null, e)
        },
        delegate: function(t, e, i, n) {
            return this.on(e, t, i, n)
        },
        undelegate: function(t, e, i) {
            return 1 === arguments.length ? this.off(t, "**") : this.off(e, t || "**", i)
        }
    });
    var Oi = ae.now()
      , Ei = /\?/
      , Pi = /(,)|(\[|{)|(}|])|"(?:[^"\\\r\n]|\\["\\\/bfnrt]|\\u[\da-fA-F]{4})*"\s*:?|true|false|null|-?(?!0\d)\d+(?:\.\d+|)(?:[eE][+-]?\d+|)/g;
    ae.parseJSON = function(e) {
        if (t.JSON && t.JSON.parse)
            return t.JSON.parse(e + "");
        var i, n = null, a = ae.trim(e + "");
        return a && !ae.trim(a.replace(Pi, function(t, e, a, r) {
            return i && e && (n = 0),
            0 === n ? t : (i = a || e,
            n += !r - !a,
            "")
        })) ? Function("return " + a)() : ae.error("Invalid JSON: " + e)
    }
    ,
    ae.parseXML = function(e) {
        var i, n;
        if (!e || "string" != typeof e)
            return null;
        try {
            t.DOMParser ? (n = new DOMParser,
            i = n.parseFromString(e, "text/xml")) : (i = new ActiveXObject("Microsoft.XMLDOM"),
            i.async = "false",
            i.loadXML(e))
        } catch (a) {
            i = void 0
        }
        return i && i.documentElement && !i.getElementsByTagName("parsererror").length || ae.error("Invalid XML: " + e),
        i
    }
    ;
    var Ni, Mi, Ri = /#.*$/, Ii = /([?&])_=[^&]*/, Bi = /^(.*?):[ \t]*([^\r\n]*)\r?$/gm, Li = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/, ji = /^(?:GET|HEAD)$/, Fi = /^\/\//, Ui = /^([\w.+-]+:)(?:\/\/(?:[^\/?#]*@|)([^\/?#:]*)(?::(\d+)|)|)/, zi = {}, Hi = {}, Wi = "*/".concat("*");
    try {
        Mi = location.href
    } catch (qi) {
        Mi = fe.createElement("a"),
        Mi.href = "",
        Mi = Mi.href
    }
    Ni = Ui.exec(Mi.toLowerCase()) || [],
    ae.extend({
        active: 0,
        lastModified: {},
        etag: {},
        ajaxSettings: {
            url: Mi,
            type: "GET",
            isLocal: Li.test(Ni[1]),
            global: !0,
            processData: !0,
            async: !0,
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            accepts: {
                "*": Wi,
                text: "text/plain",
                html: "text/html",
                xml: "application/xml, text/xml",
                json: "application/json, text/javascript"
            },
            contents: {
                xml: /xml/,
                html: /html/,
                json: /json/
            },
            responseFields: {
                xml: "responseXML",
                text: "responseText",
                json: "responseJSON"
            },
            converters: {
                "* text": String,
                "text html": !0,
                "text json": ae.parseJSON,
                "text xml": ae.parseXML
            },
            flatOptions: {
                url: !0,
                context: !0
            }
        },
        ajaxSetup: function(t, e) {
            return e ? U(U(t, ae.ajaxSettings), e) : U(ae.ajaxSettings, t)
        },
        ajaxPrefilter: j(zi),
        ajaxTransport: j(Hi),
        ajax: function(t, e) {
            function i(t, e, i, n) {
                var a, u, v, _, b, w = e;
                2 !== y && (y = 2,
                s && clearTimeout(s),
                c = void 0,
                o = n || "",
                x.readyState = t > 0 ? 4 : 0,
                a = t >= 200 && 300 > t || 304 === t,
                i && (_ = z(d, x, i)),
                _ = H(d, _, x, a),
                a ? (d.ifModified && (b = x.getResponseHeader("Last-Modified"),
                b && (ae.lastModified[r] = b),
                b = x.getResponseHeader("etag"),
                b && (ae.etag[r] = b)),
                204 === t || "HEAD" === d.type ? w = "nocontent" : 304 === t ? w = "notmodified" : (w = _.state,
                u = _.data,
                v = _.error,
                a = !v)) : (v = w,
                (t || !w) && (w = "error",
                0 > t && (t = 0))),
                x.status = t,
                x.statusText = (e || w) + "",
                a ? f.resolveWith(h, [u, w, x]) : f.rejectWith(h, [x, w, v]),
                x.statusCode(g),
                g = void 0,
                l && p.trigger(a ? "ajaxSuccess" : "ajaxError", [x, d, a ? u : v]),
                m.fireWith(h, [x, w]),
                l && (p.trigger("ajaxComplete", [x, d]),
                --ae.active || ae.event.trigger("ajaxStop")))
            }
            "object" == typeof t && (e = t,
            t = void 0),
            e = e || {};
            var n, a, r, o, s, l, c, u, d = ae.ajaxSetup({}, e), h = d.context || d, p = d.context && (h.nodeType || h.jquery) ? ae(h) : ae.event, f = ae.Deferred(), m = ae.Callbacks("once memory"), g = d.statusCode || {}, v = {}, _ = {}, y = 0, b = "canceled", x = {
                readyState: 0,
                getResponseHeader: function(t) {
                    var e;
                    if (2 === y) {
                        if (!u)
                            for (u = {}; e = Bi.exec(o); )
                                u[e[1].toLowerCase()] = e[2];
                        e = u[t.toLowerCase()]
                    }
                    return null == e ? null : e
                },
                getAllResponseHeaders: function() {
                    return 2 === y ? o : null
                },
                setRequestHeader: function(t, e) {
                    var i = t.toLowerCase();
                    return y || (t = _[i] = _[i] || t,
                    v[t] = e),
                    this
                },
                overrideMimeType: function(t) {
                    return y || (d.mimeType = t),
                    this
                },
                statusCode: function(t) {
                    var e;
                    if (t)
                        if (2 > y)
                            for (e in t)
                                g[e] = [g[e], t[e]];
                        else
                            x.always(t[x.status]);
                    return this
                },
                abort: function(t) {
                    var e = t || b;
                    return c && c.abort(e),
                    i(0, e),
                    this
                }
            };
            if (f.promise(x).complete = m.add,
            x.success = x.done,
            x.error = x.fail,
            d.url = ((t || d.url || Mi) + "").replace(Ri, "").replace(Fi, Ni[1] + "//"),
            d.type = e.method || e.type || d.method || d.type,
            d.dataTypes = ae.trim(d.dataType || "*").toLowerCase().match(ye) || [""],
            null == d.crossDomain && (n = Ui.exec(d.url.toLowerCase()),
            d.crossDomain = !(!n || n[1] === Ni[1] && n[2] === Ni[2] && (n[3] || ("http:" === n[1] ? "80" : "443")) === (Ni[3] || ("http:" === Ni[1] ? "80" : "443")))),
            d.data && d.processData && "string" != typeof d.data && (d.data = ae.param(d.data, d.traditional)),
            F(zi, d, e, x),
            2 === y)
                return x;
            l = ae.event && d.global,
            l && 0 === ae.active++ && ae.event.trigger("ajaxStart"),
            d.type = d.type.toUpperCase(),
            d.hasContent = !ji.test(d.type),
            r = d.url,
            d.hasContent || (d.data && (r = d.url += (Ei.test(r) ? "&" : "?") + d.data,
            delete d.data),
            d.cache === !1 && (d.url = Ii.test(r) ? r.replace(Ii, "$1_=" + Oi++) : r + (Ei.test(r) ? "&" : "?") + "_=" + Oi++)),
            d.ifModified && (ae.lastModified[r] && x.setRequestHeader("If-Modified-Since", ae.lastModified[r]),
            ae.etag[r] && x.setRequestHeader("If-None-Match", ae.etag[r])),
            (d.data && d.hasContent && d.contentType !== !1 || e.contentType) && x.setRequestHeader("Content-Type", d.contentType),
            x.setRequestHeader("Accept", d.dataTypes[0] && d.accepts[d.dataTypes[0]] ? d.accepts[d.dataTypes[0]] + ("*" !== d.dataTypes[0] ? ", " + Wi + "; q=0.01" : "") : d.accepts["*"]);
            for (a in d.headers)
                x.setRequestHeader(a, d.headers[a]);
            if (d.beforeSend && (d.beforeSend.call(h, x, d) === !1 || 2 === y))
                return x.abort();
            b = "abort";
            for (a in {
                success: 1,
                error: 1,
                complete: 1
            })
                x[a](d[a]);
            if (c = F(Hi, d, e, x)) {
                x.readyState = 1,
                l && p.trigger("ajaxSend", [x, d]),
                d.async && d.timeout > 0 && (s = setTimeout(function() {
                    x.abort("timeout")
                }, d.timeout));
                try {
                    y = 1,
                    c.send(v, i)
                } catch (w) {
                    if (!(2 > y))
                        throw w;
                    i(-1, w)
                }
            } else
                i(-1, "No Transport");
            return x
        },
        getJSON: function(t, e, i) {
            return ae.get(t, e, i, "json")
        },
        getScript: function(t, e) {
            return ae.get(t, void 0, e, "script")
        }
    }),
    ae.each(["get", "post"], function(t, e) {
        ae[e] = function(t, i, n, a) {
            return ae.isFunction(i) && (a = a || n,
            n = i,
            i = void 0),
            ae.ajax({
                url: t,
                type: e,
                dataType: a,
                data: i,
                success: n
            })
        }
    }),
    ae._evalUrl = function(t) {
        return ae.ajax({
            url: t,
            type: "GET",
            dataType: "script",
            async: !1,
            global: !1,
            "throws": !0
        })
    }
    ,
    ae.fn.extend({
        wrapAll: function(t) {
            if (ae.isFunction(t))
                return this.each(function(e) {
                    ae(this).wrapAll(t.call(this, e))
                });
            if (this[0]) {
                var e = ae(t, this[0].ownerDocument).eq(0).clone(!0);
                this[0].parentNode && e.insertBefore(this[0]),
                e.map(function() {
                    for (var t = this; t.firstChild && 1 === t.firstChild.nodeType; )
                        t = t.firstChild;
                    return t
                }).append(this)
            }
            return this
        },
        wrapInner: function(t) {
            return this.each(ae.isFunction(t) ? function(e) {
                ae(this).wrapInner(t.call(this, e))
            }
            : function() {
                var e = ae(this)
                  , i = e.contents();
                i.length ? i.wrapAll(t) : e.append(t)
            }
            )
        },
        wrap: function(t) {
            var e = ae.isFunction(t);
            return this.each(function(i) {
                ae(this).wrapAll(e ? t.call(this, i) : t)
            })
        },
        unwrap: function() {
            return this.parent().each(function() {
                ae.nodeName(this, "body") || ae(this).replaceWith(this.childNodes)
            }).end()
        }
    }),
    ae.expr.filters.hidden = function(t) {
        return t.offsetWidth <= 0 && t.offsetHeight <= 0 || !ie.reliableHiddenOffsets() && "none" === (t.style && t.style.display || ae.css(t, "display"))
    }
    ,
    ae.expr.filters.visible = function(t) {
        return !ae.expr.filters.hidden(t)
    }
    ;
    var Yi = /%20/g
      , Xi = /\[\]$/
      , Vi = /\r?\n/g
      , Gi = /^(?:submit|button|image|reset|file)$/i
      , Qi = /^(?:input|select|textarea|keygen)/i;
    ae.param = function(t, e) {
        var i, n = [], a = function(t, e) {
            e = ae.isFunction(e) ? e() : null == e ? "" : e,
            n[n.length] = encodeURIComponent(t) + "=" + encodeURIComponent(e)
        };
        if (void 0 === e && (e = ae.ajaxSettings && ae.ajaxSettings.traditional),
        ae.isArray(t) || t.jquery && !ae.isPlainObject(t))
            ae.each(t, function() {
                a(this.name, this.value)
            });
        else
            for (i in t)
                W(i, t[i], e, a);
        return n.join("&").replace(Yi, "+")
    }
    ,
    ae.fn.extend({
        serialize: function() {
            return ae.param(this.serializeArray())
        },
        serializeArray: function() {
            return this.map(function() {
                var t = ae.prop(this, "elements");
                return t ? ae.makeArray(t) : this
            }).filter(function() {
                var t = this.type;
                return this.name && !ae(this).is(":disabled") && Qi.test(this.nodeName) && !Gi.test(t) && (this.checked || !Oe.test(t))
            }).map(function(t, e) {
                var i = ae(this).val();
                return null == i ? null : ae.isArray(i) ? ae.map(i, function(t) {
                    return {
                        name: e.name,
                        value: t.replace(Vi, "\r\n")
                    }
                }) : {
                    name: e.name,
                    value: i.replace(Vi, "\r\n")
                }
            }).get()
        }
    }),
    ae.ajaxSettings.xhr = void 0 !== t.ActiveXObject ? function() {
        return !this.isLocal && /^(get|post|head|put|delete|options)$/i.test(this.type) && q() || Y()
    }
    : q;
    var Ji = 0
      , Ki = {}
      , Zi = ae.ajaxSettings.xhr();
    t.attachEvent && t.attachEvent("onunload", function() {
        for (var t in Ki)
            Ki[t](void 0, !0)
    }),
    ie.cors = !!Zi && "withCredentials"in Zi,
    Zi = ie.ajax = !!Zi,
    Zi && ae.ajaxTransport(function(t) {
        if (!t.crossDomain || ie.cors) {
            var e;
            return {
                send: function(i, n) {
                    var a, r = t.xhr(), o = ++Ji;
                    if (r.open(t.type, t.url, t.async, t.username, t.password),
                    t.xhrFields)
                        for (a in t.xhrFields)
                            r[a] = t.xhrFields[a];
                    t.mimeType && r.overrideMimeType && r.overrideMimeType(t.mimeType),
                    t.crossDomain || i["X-Requested-With"] || (i["X-Requested-With"] = "XMLHttpRequest");
                    for (a in i)
                        void 0 !== i[a] && r.setRequestHeader(a, i[a] + "");
                    r.send(t.hasContent && t.data || null),
                    e = function(i, a) {
                        var s, l, c;
                        if (e && (a || 4 === r.readyState))
                            if (delete Ki[o],
                            e = void 0,
                            r.onreadystatechange = ae.noop,
                            a)
                                4 !== r.readyState && r.abort();
                            else {
                                c = {},
                                s = r.status,
                                "string" == typeof r.responseText && (c.text = r.responseText);
                                try {
                                    l = r.statusText
                                } catch (u) {
                                    l = ""
                                }
                                s || !t.isLocal || t.crossDomain ? 1223 === s && (s = 204) : s = c.text ? 200 : 404
                            }
                        c && n(s, l, c, r.getAllResponseHeaders())
                    }
                    ,
                    t.async ? 4 === r.readyState ? setTimeout(e) : r.onreadystatechange = Ki[o] = e : e()
                },
                abort: function() {
                    e && e(void 0, !0)
                }
            }
        }
    }),
    ae.ajaxSetup({
        accepts: {
            script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"
        },
        contents: {
            script: /(?:java|ecma)script/
        },
        converters: {
            "text script": function(t) {
                return ae.globalEval(t),
                t
            }
        }
    }),
    ae.ajaxPrefilter("script", function(t) {
        void 0 === t.cache && (t.cache = !1),
        t.crossDomain && (t.type = "GET",
        t.global = !1)
    }),
    ae.ajaxTransport("script", function(t) {
        if (t.crossDomain) {
            var e, i = fe.head || ae("head")[0] || fe.documentElement;
            return {
                send: function(n, a) {
                    e = fe.createElement("script"),
                    e.async = !0,
                    t.scriptCharset && (e.charset = t.scriptCharset),
                    e.src = t.url,
                    e.onload = e.onreadystatechange = function(t, i) {
                        (i || !e.readyState || /loaded|complete/.test(e.readyState)) && (e.onload = e.onreadystatechange = null,
                        e.parentNode && e.parentNode.removeChild(e),
                        e = null,
                        i || a(200, "success"))
                    }
                    ,
                    i.insertBefore(e, i.firstChild)
                },
                abort: function() {
                    e && e.onload(void 0, !0)
                }
            }
        }
    });
    var tn = []
      , en = /(=)\?(?=&|$)|\?\?/;
    ae.ajaxSetup({
        jsonp: "callback",
        jsonpCallback: function() {
            var t = tn.pop() || ae.expando + "_" + Oi++;
            return this[t] = !0,
            t
        }
    }),
    ae.ajaxPrefilter("json jsonp", function(e, i, n) {
        var a, r, o, s = e.jsonp !== !1 && (en.test(e.url) ? "url" : "string" == typeof e.data && !(e.contentType || "").indexOf("application/x-www-form-urlencoded") && en.test(e.data) && "data");
        return s || "jsonp" === e.dataTypes[0] ? (a = e.jsonpCallback = ae.isFunction(e.jsonpCallback) ? e.jsonpCallback() : e.jsonpCallback,
        s ? e[s] = e[s].replace(en, "$1" + a) : e.jsonp !== !1 && (e.url += (Ei.test(e.url) ? "&" : "?") + e.jsonp + "=" + a),
        e.converters["script json"] = function() {
            return o || ae.error(a + " was not called"),
            o[0]
        }
        ,
        e.dataTypes[0] = "json",
        r = t[a],
        t[a] = function() {
            o = arguments
        }
        ,
        n.always(function() {
            t[a] = r,
            e[a] && (e.jsonpCallback = i.jsonpCallback,
            tn.push(a)),
            o && ae.isFunction(r) && r(o[0]),
            o = r = void 0
        }),
        "script") : void 0
    }),
    ae.parseHTML = function(t, e, i) {
        if (!t || "string" != typeof t)
            return null;
        "boolean" == typeof e && (i = e,
        e = !1),
        e = e || fe;
        var n = de.exec(t)
          , a = !i && [];
        return n ? [e.createElement(n[1])] : (n = ae.buildFragment([t], e, a),
        a && a.length && ae(a).remove(),
        ae.merge([], n.childNodes))
    }
    ;
    var nn = ae.fn.load;
    ae.fn.load = function(t, e, i) {
        if ("string" != typeof t && nn)
            return nn.apply(this, arguments);
        var n, a, r, o = this, s = t.indexOf(" ");
        return s >= 0 && (n = ae.trim(t.slice(s, t.length)),
        t = t.slice(0, s)),
        ae.isFunction(e) ? (i = e,
        e = void 0) : e && "object" == typeof e && (r = "POST"),
        o.length > 0 && ae.ajax({
            url: t,
            type: r,
            dataType: "html",
            data: e
        }).done(function(t) {
            a = arguments,
            o.html(n ? ae("<div>").append(ae.parseHTML(t)).find(n) : t)
        }).complete(i && function(t, e) {
            o.each(i, a || [t.responseText, e, t])
        }
        ),
        this
    }
    ,
    ae.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function(t, e) {
        ae.fn[e] = function(t) {
            return this.on(e, t)
        }
    }),
    ae.expr.filters.animated = function(t) {
        return ae.grep(ae.timers, function(e) {
            return t === e.elem
        }).length
    }
    ;
    var an = t.document.documentElement;
    ae.offset = {
        setOffset: function(t, e, i) {
            var n, a, r, o, s, l, c, u = ae.css(t, "position"), d = ae(t), h = {};
            "static" === u && (t.style.position = "relative"),
            s = d.offset(),
            r = ae.css(t, "top"),
            l = ae.css(t, "left"),
            c = ("absolute" === u || "fixed" === u) && ae.inArray("auto", [r, l]) > -1,
            c ? (n = d.position(),
            o = n.top,
            a = n.left) : (o = parseFloat(r) || 0,
            a = parseFloat(l) || 0),
            ae.isFunction(e) && (e = e.call(t, i, s)),
            null != e.top && (h.top = e.top - s.top + o),
            null != e.left && (h.left = e.left - s.left + a),
            "using"in e ? e.using.call(t, h) : d.css(h)
        }
    },
    ae.fn.extend({
        offset: function(t) {
            if (arguments.length)
                return void 0 === t ? this : this.each(function(e) {
                    ae.offset.setOffset(this, t, e)
                });
            var e, i, n = {
                top: 0,
                left: 0
            }, a = this[0], r = a && a.ownerDocument;
            return r ? (e = r.documentElement,
            ae.contains(e, a) ? (typeof a.getBoundingClientRect !== Te && (n = a.getBoundingClientRect()),
            i = X(r),
            {
                top: n.top + (i.pageYOffset || e.scrollTop) - (e.clientTop || 0),
                left: n.left + (i.pageXOffset || e.scrollLeft) - (e.clientLeft || 0)
            }) : n) : void 0
        },
        position: function() {
            if (this[0]) {
                var t, e, i = {
                    top: 0,
                    left: 0
                }, n = this[0];
                return "fixed" === ae.css(n, "position") ? e = n.getBoundingClientRect() : (t = this.offsetParent(),
                e = this.offset(),
                ae.nodeName(t[0], "html") || (i = t.offset()),
                i.top += ae.css(t[0], "borderTopWidth", !0),
                i.left += ae.css(t[0], "borderLeftWidth", !0)),
                {
                    top: e.top - i.top - ae.css(n, "marginTop", !0),
                    left: e.left - i.left - ae.css(n, "marginLeft", !0)
                }
            }
        },
        offsetParent: function() {
            return this.map(function() {
                for (var t = this.offsetParent || an; t && !ae.nodeName(t, "html") && "static" === ae.css(t, "position"); )
                    t = t.offsetParent;
                return t || an
            })
        }
    }),
    ae.each({
        scrollLeft: "pageXOffset",
        scrollTop: "pageYOffset"
    }, function(t, e) {
        var i = /Y/.test(e);
        ae.fn[t] = function(n) {
            return Ae(this, function(t, n, a) {
                var r = X(t);
                return void 0 === a ? r ? e in r ? r[e] : r.document.documentElement[n] : t[n] : void (r ? r.scrollTo(i ? ae(r).scrollLeft() : a, i ? a : ae(r).scrollTop()) : t[n] = a)
            }, t, n, arguments.length, null)
        }
    }),
    ae.each(["top", "left"], function(t, e) {
        ae.cssHooks[e] = k(ie.pixelPosition, function(t, i) {
            return i ? (i = ei(t, e),
            ni.test(i) ? ae(t).position()[e] + "px" : i) : void 0
        })
    }),
    ae.each({
        Height: "height",
        Width: "width"
    }, function(t, e) {
        ae.each({
            padding: "inner" + t,
            content: e,
            "": "outer" + t
        }, function(i, n) {
            ae.fn[n] = function(n, a) {
                var r = arguments.length && (i || "boolean" != typeof n)
                  , o = i || (n === !0 || a === !0 ? "margin" : "border");
                return Ae(this, function(e, i, n) {
                    var a;
                    return ae.isWindow(e) ? e.document.documentElement["client" + t] : 9 === e.nodeType ? (a = e.documentElement,
                    Math.max(e.body["scroll" + t], a["scroll" + t], e.body["offset" + t], a["offset" + t], a["client" + t])) : void 0 === n ? ae.css(e, i, o) : ae.style(e, i, n, o)
                }, e, r ? n : void 0, r, null)
            }
        })
    }),
    ae.fn.size = function() {
        return this.length
    }
    ,
    ae.fn.andSelf = ae.fn.addBack,
    "function" == typeof define && define.amd && define("jquery", [], function() {
        return ae
    });
    var rn = t.jQuery
      , on = t.$;
    return ae.noConflict = function(e) {
        return t.$ === ae && (t.$ = on),
        e && t.jQuery === ae && (t.jQuery = rn),
        ae
    }
    ,
    typeof e === Te && (t.jQuery = t.$ = ae),
    ae
});
var _gsScope = "undefined" != typeof module && module.exports && "undefined" != typeof global ? global : this || window;
(_gsScope._gsQueue || (_gsScope._gsQueue = [])).push(function() {
    "use strict";
    _gsScope._gsDefine("TweenMax", ["core.Animation", "core.SimpleTimeline", "TweenLite"], function(t, e, i) {
        var n = function(t) {
            var e, i = [], n = t.length;
            for (e = 0; e !== n; i.push(t[e++]))
                ;
            return i
        }
          , a = function(t, e, n) {
            i.call(this, t, e, n),
            this._cycle = 0,
            this._yoyo = this.vars.yoyo === !0,
            this._repeat = this.vars.repeat || 0,
            this._repeatDelay = this.vars.repeatDelay || 0,
            this._dirty = !0,
            this.render = a.prototype.render
        }
          , r = 1e-10
          , o = i._internals
          , s = o.isSelector
          , l = o.isArray
          , c = a.prototype = i.to({}, .1, {})
          , u = [];
        a.version = "1.17.0",
        c.constructor = a,
        c.kill()._gc = !1,
        a.killTweensOf = a.killDelayedCallsTo = i.killTweensOf,
        a.getTweensOf = i.getTweensOf,
        a.lagSmoothing = i.lagSmoothing,
        a.ticker = i.ticker,
        a.render = i.render,
        c.invalidate = function() {
            return this._yoyo = this.vars.yoyo === !0,
            this._repeat = this.vars.repeat || 0,
            this._repeatDelay = this.vars.repeatDelay || 0,
            this._uncache(!0),
            i.prototype.invalidate.call(this)
        }
        ,
        c.updateTo = function(t, e) {
            var n, a = this.ratio, r = this.vars.immediateRender || t.immediateRender;
            e && this._startTime < this._timeline._time && (this._startTime = this._timeline._time,
            this._uncache(!1),
            this._gc ? this._enabled(!0, !1) : this._timeline.insert(this, this._startTime - this._delay));
            for (n in t)
                this.vars[n] = t[n];
            if (this._initted || r)
                if (e)
                    this._initted = !1,
                    r && this.render(0, !0, !0);
                else if (this._gc && this._enabled(!0, !1),
                this._notifyPluginsOfEnabled && this._firstPT && i._onPluginEvent("_onDisable", this),
                this._time / this._duration > .998) {
                    var o = this._time;
                    this.render(0, !0, !1),
                    this._initted = !1,
                    this.render(o, !0, !1)
                } else if (this._time > 0 || r) {
                    this._initted = !1,
                    this._init();
                    for (var s, l = 1 / (1 - a), c = this._firstPT; c; )
                        s = c.s + c.c,
                        c.c *= l,
                        c.s = s - c.c,
                        c = c._next
                }
            return this
        }
        ,
        c.render = function(t, e, i) {
            this._initted || 0 === this._duration && this.vars.repeat && this.invalidate();
            var n, a, s, l, c, u, d, h, p = this._dirty ? this.totalDuration() : this._totalDuration, f = this._time, m = this._totalTime, g = this._cycle, v = this._duration, _ = this._rawPrevTime;
            if (t >= p ? (this._totalTime = p,
            this._cycle = this._repeat,
            this._yoyo && 0 !== (1 & this._cycle) ? (this._time = 0,
            this.ratio = this._ease._calcEnd ? this._ease.getRatio(0) : 0) : (this._time = v,
            this.ratio = this._ease._calcEnd ? this._ease.getRatio(1) : 1),
            this._reversed || (n = !0,
            a = "onComplete",
            i = i || this._timeline.autoRemoveChildren),
            0 === v && (this._initted || !this.vars.lazy || i) && (this._startTime === this._timeline._duration && (t = 0),
            (0 === t || 0 > _ || _ === r) && _ !== t && (i = !0,
            _ > r && (a = "onReverseComplete")),
            this._rawPrevTime = h = !e || t || _ === t ? t : r)) : 1e-7 > t ? (this._totalTime = this._time = this._cycle = 0,
            this.ratio = this._ease._calcEnd ? this._ease.getRatio(0) : 0,
            (0 !== m || 0 === v && _ > 0) && (a = "onReverseComplete",
            n = this._reversed),
            0 > t && (this._active = !1,
            0 === v && (this._initted || !this.vars.lazy || i) && (_ >= 0 && (i = !0),
            this._rawPrevTime = h = !e || t || _ === t ? t : r)),
            this._initted || (i = !0)) : (this._totalTime = this._time = t,
            0 !== this._repeat && (l = v + this._repeatDelay,
            this._cycle = this._totalTime / l >> 0,
            0 !== this._cycle && this._cycle === this._totalTime / l && this._cycle--,
            this._time = this._totalTime - this._cycle * l,
            this._yoyo && 0 !== (1 & this._cycle) && (this._time = v - this._time),
            this._time > v ? this._time = v : 0 > this._time && (this._time = 0)),
            this._easeType ? (c = this._time / v,
            u = this._easeType,
            d = this._easePower,
            (1 === u || 3 === u && c >= .5) && (c = 1 - c),
            3 === u && (c *= 2),
            1 === d ? c *= c : 2 === d ? c *= c * c : 3 === d ? c *= c * c * c : 4 === d && (c *= c * c * c * c),
            this.ratio = 1 === u ? 1 - c : 2 === u ? c : .5 > this._time / v ? c / 2 : 1 - c / 2) : this.ratio = this._ease.getRatio(this._time / v)),
            f === this._time && !i && g === this._cycle)
                return void (m !== this._totalTime && this._onUpdate && (e || this._callback("onUpdate")));
            if (!this._initted) {
                if (this._init(),
                !this._initted || this._gc)
                    return;
                if (!i && this._firstPT && (this.vars.lazy !== !1 && this._duration || this.vars.lazy && !this._duration))
                    return this._time = f,
                    this._totalTime = m,
                    this._rawPrevTime = _,
                    this._cycle = g,
                    o.lazyTweens.push(this),
                    void (this._lazy = [t, e]);
                this._time && !n ? this.ratio = this._ease.getRatio(this._time / v) : n && this._ease._calcEnd && (this.ratio = this._ease.getRatio(0 === this._time ? 0 : 1))
            }
            for (this._lazy !== !1 && (this._lazy = !1),
            this._active || !this._paused && this._time !== f && t >= 0 && (this._active = !0),
            0 === m && (2 === this._initted && t > 0 && this._init(),
            this._startAt && (t >= 0 ? this._startAt.render(t, e, i) : a || (a = "_dummyGS")),
            this.vars.onStart && (0 !== this._totalTime || 0 === v) && (e || this._callback("onStart"))),
            s = this._firstPT; s; )
                s.f ? s.t[s.p](s.c * this.ratio + s.s) : s.t[s.p] = s.c * this.ratio + s.s,
                s = s._next;
            this._onUpdate && (0 > t && this._startAt && this._startTime && this._startAt.render(t, e, i),
            e || (this._totalTime !== m || n) && this._callback("onUpdate")),
            this._cycle !== g && (e || this._gc || this.vars.onRepeat && this._callback("onRepeat")),
            a && (!this._gc || i) && (0 > t && this._startAt && !this._onUpdate && this._startTime && this._startAt.render(t, e, i),
            n && (this._timeline.autoRemoveChildren && this._enabled(!1, !1),
            this._active = !1),
            !e && this.vars[a] && this._callback(a),
            0 === v && this._rawPrevTime === r && h !== r && (this._rawPrevTime = 0))
        }
        ,
        a.to = function(t, e, i) {
            return new a(t,e,i)
        }
        ,
        a.from = function(t, e, i) {
            return i.runBackwards = !0,
            i.immediateRender = 0 != i.immediateRender,
            new a(t,e,i)
        }
        ,
        a.fromTo = function(t, e, i, n) {
            return n.startAt = i,
            n.immediateRender = 0 != n.immediateRender && 0 != i.immediateRender,
            new a(t,e,n)
        }
        ,
        a.staggerTo = a.allTo = function(t, e, r, o, c, d, h) {
            o = o || 0;
            var p, f, m, g, v = r.delay || 0, _ = [], y = function() {
                r.onComplete && r.onComplete.apply(r.onCompleteScope || this, arguments),
                c.apply(h || r.callbackScope || this, d || u)
            };
            for (l(t) || ("string" == typeof t && (t = i.selector(t) || t),
            s(t) && (t = n(t))),
            t = t || [],
            0 > o && (t = n(t),
            t.reverse(),
            o *= -1),
            p = t.length - 1,
            m = 0; p >= m; m++) {
                f = {};
                for (g in r)
                    f[g] = r[g];
                f.delay = v,
                m === p && c && (f.onComplete = y),
                _[m] = new a(t[m],e,f),
                v += o
            }
            return _
        }
        ,
        a.staggerFrom = a.allFrom = function(t, e, i, n, r, o, s) {
            return i.runBackwards = !0,
            i.immediateRender = 0 != i.immediateRender,
            a.staggerTo(t, e, i, n, r, o, s)
        }
        ,
        a.staggerFromTo = a.allFromTo = function(t, e, i, n, r, o, s, l) {
            return n.startAt = i,
            n.immediateRender = 0 != n.immediateRender && 0 != i.immediateRender,
            a.staggerTo(t, e, n, r, o, s, l)
        }
        ,
        a.delayedCall = function(t, e, i, n, r) {
            return new a(e,0,{
                delay: t,
                onComplete: e,
                onCompleteParams: i,
                callbackScope: n,
                onReverseComplete: e,
                onReverseCompleteParams: i,
                immediateRender: !1,
                useFrames: r,
                overwrite: 0
            })
        }
        ,
        a.set = function(t, e) {
            return new a(t,0,e)
        }
        ,
        a.isTweening = function(t) {
            return i.getTweensOf(t, !0).length > 0
        }
        ;
        var d = function(t, e) {
            for (var n = [], a = 0, r = t._first; r; )
                r instanceof i ? n[a++] = r : (e && (n[a++] = r),
                n = n.concat(d(r, e)),
                a = n.length),
                r = r._next;
            return n
        }
          , h = a.getAllTweens = function(e) {
            return d(t._rootTimeline, e).concat(d(t._rootFramesTimeline, e))
        }
        ;
        a.killAll = function(t, i, n, a) {
            null == i && (i = !0),
            null == n && (n = !0);
            var r, o, s, l = h(0 != a), c = l.length, u = i && n && a;
            for (s = 0; c > s; s++)
                o = l[s],
                (u || o instanceof e || (r = o.target === o.vars.onComplete) && n || i && !r) && (t ? o.totalTime(o._reversed ? 0 : o.totalDuration()) : o._enabled(!1, !1))
        }
        ,
        a.killChildTweensOf = function(t, e) {
            if (null != t) {
                var r, c, u, d, h, p = o.tweenLookup;
                if ("string" == typeof t && (t = i.selector(t) || t),
                s(t) && (t = n(t)),
                l(t))
                    for (d = t.length; --d > -1; )
                        a.killChildTweensOf(t[d], e);
                else {
                    r = [];
                    for (u in p)
                        for (c = p[u].target.parentNode; c; )
                            c === t && (r = r.concat(p[u].tweens)),
                            c = c.parentNode;
                    for (h = r.length,
                    d = 0; h > d; d++)
                        e && r[d].totalTime(r[d].totalDuration()),
                        r[d]._enabled(!1, !1)
                }
            }
        }
        ;
        var p = function(t, i, n, a) {
            i = i !== !1,
            n = n !== !1,
            a = a !== !1;
            for (var r, o, s = h(a), l = i && n && a, c = s.length; --c > -1; )
                o = s[c],
                (l || o instanceof e || (r = o.target === o.vars.onComplete) && n || i && !r) && o.paused(t)
        };
        return a.pauseAll = function(t, e, i) {
            p(!0, t, e, i)
        }
        ,
        a.resumeAll = function(t, e, i) {
            p(!1, t, e, i)
        }
        ,
        a.globalTimeScale = function(e) {
            var n = t._rootTimeline
              , a = i.ticker.time;
            return arguments.length ? (e = e || r,
            n._startTime = a - (a - n._startTime) * n._timeScale / e,
            n = t._rootFramesTimeline,
            a = i.ticker.frame,
            n._startTime = a - (a - n._startTime) * n._timeScale / e,
            n._timeScale = t._rootTimeline._timeScale = e,
            e) : n._timeScale
        }
        ,
        c.progress = function(t) {
            return arguments.length ? this.totalTime(this.duration() * (this._yoyo && 0 !== (1 & this._cycle) ? 1 - t : t) + this._cycle * (this._duration + this._repeatDelay), !1) : this._time / this.duration()
        }
        ,
        c.totalProgress = function(t) {
            return arguments.length ? this.totalTime(this.totalDuration() * t, !1) : this._totalTime / this.totalDuration()
        }
        ,
        c.time = function(t, e) {
            return arguments.length ? (this._dirty && this.totalDuration(),
            t > this._duration && (t = this._duration),
            this._yoyo && 0 !== (1 & this._cycle) ? t = this._duration - t + this._cycle * (this._duration + this._repeatDelay) : 0 !== this._repeat && (t += this._cycle * (this._duration + this._repeatDelay)),
            this.totalTime(t, e)) : this._time
        }
        ,
        c.duration = function(e) {
            return arguments.length ? t.prototype.duration.call(this, e) : this._duration
        }
        ,
        c.totalDuration = function(t) {
            return arguments.length ? -1 === this._repeat ? this : this.duration((t - this._repeat * this._repeatDelay) / (this._repeat + 1)) : (this._dirty && (this._totalDuration = -1 === this._repeat ? 999999999999 : this._duration * (this._repeat + 1) + this._repeatDelay * this._repeat,
            this._dirty = !1),
            this._totalDuration)
        }
        ,
        c.repeat = function(t) {
            return arguments.length ? (this._repeat = t,
            this._uncache(!0)) : this._repeat
        }
        ,
        c.repeatDelay = function(t) {
            return arguments.length ? (this._repeatDelay = t,
            this._uncache(!0)) : this._repeatDelay
        }
        ,
        c.yoyo = function(t) {
            return arguments.length ? (this._yoyo = t,
            this) : this._yoyo
        }
        ,
        a
    }, !0),
    _gsScope._gsDefine("TimelineLite", ["core.Animation", "core.SimpleTimeline", "TweenLite"], function(t, e, i) {
        var n = function(t) {
            e.call(this, t),
            this._labels = {},
            this.autoRemoveChildren = this.vars.autoRemoveChildren === !0,
            this.smoothChildTiming = this.vars.smoothChildTiming === !0,
            this._sortChildren = !0,
            this._onUpdate = this.vars.onUpdate;
            var i, n, a = this.vars;
            for (n in a)
                i = a[n],
                l(i) && -1 !== i.join("").indexOf("{self}") && (a[n] = this._swapSelfInParams(i));
            l(a.tweens) && this.add(a.tweens, 0, a.align, a.stagger)
        }
          , a = 1e-10
          , r = i._internals
          , o = n._internals = {}
          , s = r.isSelector
          , l = r.isArray
          , c = r.lazyTweens
          , u = r.lazyRender
          , d = []
          , h = _gsScope._gsDefine.globals
          , p = function(t) {
            var e, i = {};
            for (e in t)
                i[e] = t[e];
            return i
        }
          , f = o.pauseCallback = function(t, e, i, n) {
            var r, o = t._timeline, s = o._totalTime, l = t._startTime, c = 0 > t._rawPrevTime || 0 === t._rawPrevTime && o._reversed, u = c ? 0 : a, h = c ? a : 0;
            if (e || !this._forcingPlayhead) {
                for (o.pause(l),
                r = t._prev; r && r._startTime === l; )
                    r._rawPrevTime = h,
                    r = r._prev;
                for (r = t._next; r && r._startTime === l; )
                    r._rawPrevTime = u,
                    r = r._next;
                e && e.apply(n || o.vars.callbackScope || o, i || d),
                (this._forcingPlayhead || !o._paused) && o.seek(s)
            }
        }
          , m = function(t) {
            var e, i = [], n = t.length;
            for (e = 0; e !== n; i.push(t[e++]))
                ;
            return i
        }
          , g = n.prototype = new e;
        return n.version = "1.17.0",
        g.constructor = n,
        g.kill()._gc = g._forcingPlayhead = !1,
        g.to = function(t, e, n, a) {
            var r = n.repeat && h.TweenMax || i;
            return e ? this.add(new r(t,e,n), a) : this.set(t, n, a)
        }
        ,
        g.from = function(t, e, n, a) {
            return this.add((n.repeat && h.TweenMax || i).from(t, e, n), a)
        }
        ,
        g.fromTo = function(t, e, n, a, r) {
            var o = a.repeat && h.TweenMax || i;
            return e ? this.add(o.fromTo(t, e, n, a), r) : this.set(t, a, r)
        }
        ,
        g.staggerTo = function(t, e, a, r, o, l, c, u) {
            var d, h = new n({
                onComplete: l,
                onCompleteParams: c,
                callbackScope: u,
                smoothChildTiming: this.smoothChildTiming
            });
            for ("string" == typeof t && (t = i.selector(t) || t),
            t = t || [],
            s(t) && (t = m(t)),
            r = r || 0,
            0 > r && (t = m(t),
            t.reverse(),
            r *= -1),
            d = 0; t.length > d; d++)
                a.startAt && (a.startAt = p(a.startAt)),
                h.to(t[d], e, p(a), d * r);
            return this.add(h, o)
        }
        ,
        g.staggerFrom = function(t, e, i, n, a, r, o, s) {
            return i.immediateRender = 0 != i.immediateRender,
            i.runBackwards = !0,
            this.staggerTo(t, e, i, n, a, r, o, s)
        }
        ,
        g.staggerFromTo = function(t, e, i, n, a, r, o, s, l) {
            return n.startAt = i,
            n.immediateRender = 0 != n.immediateRender && 0 != i.immediateRender,
            this.staggerTo(t, e, n, a, r, o, s, l)
        }
        ,
        g.call = function(t, e, n, a) {
            return this.add(i.delayedCall(0, t, e, n), a)
        }
        ,
        g.set = function(t, e, n) {
            return n = this._parseTimeOrLabel(n, 0, !0),
            null == e.immediateRender && (e.immediateRender = n === this._time && !this._paused),
            this.add(new i(t,0,e), n)
        }
        ,
        n.exportRoot = function(t, e) {
            t = t || {},
            null == t.smoothChildTiming && (t.smoothChildTiming = !0);
            var a, r, o = new n(t), s = o._timeline;
            for (null == e && (e = !0),
            s._remove(o, !0),
            o._startTime = 0,
            o._rawPrevTime = o._time = o._totalTime = s._time,
            a = s._first; a; )
                r = a._next,
                e && a instanceof i && a.target === a.vars.onComplete || o.add(a, a._startTime - a._delay),
                a = r;
            return s.add(o, 0),
            o
        }
        ,
        g.add = function(a, r, o, s) {
            var c, u, d, h, p, f;
            if ("number" != typeof r && (r = this._parseTimeOrLabel(r, 0, !0, a)),
            !(a instanceof t)) {
                if (a instanceof Array || a && a.push && l(a)) {
                    for (o = o || "normal",
                    s = s || 0,
                    c = r,
                    u = a.length,
                    d = 0; u > d; d++)
                        l(h = a[d]) && (h = new n({
                            tweens: h
                        })),
                        this.add(h, c),
                        "string" != typeof h && "function" != typeof h && ("sequence" === o ? c = h._startTime + h.totalDuration() / h._timeScale : "start" === o && (h._startTime -= h.delay())),
                        c += s;
                    return this._uncache(!0)
                }
                if ("string" == typeof a)
                    return this.addLabel(a, r);
                if ("function" != typeof a)
                    throw "Cannot add " + a + " into the timeline; it is not a tween, timeline, function, or string.";
                a = i.delayedCall(0, a)
            }
            if (e.prototype.add.call(this, a, r),
            (this._gc || this._time === this._duration) && !this._paused && this._duration < this.duration())
                for (p = this,
                f = p.rawTime() > a._startTime; p._timeline; )
                    f && p._timeline.smoothChildTiming ? p.totalTime(p._totalTime, !0) : p._gc && p._enabled(!0, !1),
                    p = p._timeline;
            return this
        }
        ,
        g.remove = function(e) {
            if (e instanceof t)
                return this._remove(e, !1);
            if (e instanceof Array || e && e.push && l(e)) {
                for (var i = e.length; --i > -1; )
                    this.remove(e[i]);
                return this
            }
            return "string" == typeof e ? this.removeLabel(e) : this.kill(null, e)
        }
        ,
        g._remove = function(t, i) {
            e.prototype._remove.call(this, t, i);
            var n = this._last;
            return n ? this._time > n._startTime + n._totalDuration / n._timeScale && (this._time = this.duration(),
            this._totalTime = this._totalDuration) : this._time = this._totalTime = this._duration = this._totalDuration = 0,
            this
        }
        ,
        g.append = function(t, e) {
            return this.add(t, this._parseTimeOrLabel(null, e, !0, t))
        }
        ,
        g.insert = g.insertMultiple = function(t, e, i, n) {
            return this.add(t, e || 0, i, n)
        }
        ,
        g.appendMultiple = function(t, e, i, n) {
            return this.add(t, this._parseTimeOrLabel(null, e, !0, t), i, n)
        }
        ,
        g.addLabel = function(t, e) {
            return this._labels[t] = this._parseTimeOrLabel(e),
            this
        }
        ,
        g.addPause = function(t, e, n, a) {
            var r = i.delayedCall(0, f, ["{self}", e, n, a], this);
            return r.data = "isPause",
            this.add(r, t)
        }
        ,
        g.removeLabel = function(t) {
            return delete this._labels[t],
            this
        }
        ,
        g.getLabelTime = function(t) {
            return null != this._labels[t] ? this._labels[t] : -1
        }
        ,
        g._parseTimeOrLabel = function(e, i, n, a) {
            var r;
            if (a instanceof t && a.timeline === this)
                this.remove(a);
            else if (a && (a instanceof Array || a.push && l(a)))
                for (r = a.length; --r > -1; )
                    a[r]instanceof t && a[r].timeline === this && this.remove(a[r]);
            if ("string" == typeof i)
                return this._parseTimeOrLabel(i, n && "number" == typeof e && null == this._labels[i] ? e - this.duration() : 0, n);
            if (i = i || 0,
            "string" != typeof e || !isNaN(e) && null == this._labels[e])
                null == e && (e = this.duration());
            else {
                if (r = e.indexOf("="),
                -1 === r)
                    return null == this._labels[e] ? n ? this._labels[e] = this.duration() + i : i : this._labels[e] + i;
                i = parseInt(e.charAt(r - 1) + "1", 10) * Number(e.substr(r + 1)),
                e = r > 1 ? this._parseTimeOrLabel(e.substr(0, r - 1), 0, n) : this.duration()
            }
            return Number(e) + i
        }
        ,
        g.seek = function(t, e) {
            return this.totalTime("number" == typeof t ? t : this._parseTimeOrLabel(t), e !== !1)
        }
        ,
        g.stop = function() {
            return this.paused(!0)
        }
        ,
        g.gotoAndPlay = function(t, e) {
            return this.play(t, e)
        }
        ,
        g.gotoAndStop = function(t, e) {
            return this.pause(t, e)
        }
        ,
        g.render = function(t, e, i) {
            this._gc && this._enabled(!0, !1);
            var n, r, o, s, l, d = this._dirty ? this.totalDuration() : this._totalDuration, h = this._time, p = this._startTime, f = this._timeScale, m = this._paused;
            if (t >= d)
                this._totalTime = this._time = d,
                this._reversed || this._hasPausedChild() || (r = !0,
                s = "onComplete",
                l = !!this._timeline.autoRemoveChildren,
                0 === this._duration && (0 === t || 0 > this._rawPrevTime || this._rawPrevTime === a) && this._rawPrevTime !== t && this._first && (l = !0,
                this._rawPrevTime > a && (s = "onReverseComplete"))),
                this._rawPrevTime = this._duration || !e || t || this._rawPrevTime === t ? t : a,
                t = d + 1e-4;
            else if (1e-7 > t)
                if (this._totalTime = this._time = 0,
                (0 !== h || 0 === this._duration && this._rawPrevTime !== a && (this._rawPrevTime > 0 || 0 > t && this._rawPrevTime >= 0)) && (s = "onReverseComplete",
                r = this._reversed),
                0 > t)
                    this._active = !1,
                    this._timeline.autoRemoveChildren && this._reversed ? (l = r = !0,
                    s = "onReverseComplete") : this._rawPrevTime >= 0 && this._first && (l = !0),
                    this._rawPrevTime = t;
                else {
                    if (this._rawPrevTime = this._duration || !e || t || this._rawPrevTime === t ? t : a,
                    0 === t && r)
                        for (n = this._first; n && 0 === n._startTime; )
                            n._duration || (r = !1),
                            n = n._next;
                    t = 0,
                    this._initted || (l = !0)
                }
            else
                this._totalTime = this._time = this._rawPrevTime = t;
            if (this._time !== h && this._first || i || l) {
                if (this._initted || (this._initted = !0),
                this._active || !this._paused && this._time !== h && t > 0 && (this._active = !0),
                0 === h && this.vars.onStart && 0 !== this._time && (e || this._callback("onStart")),
                this._time >= h)
                    for (n = this._first; n && (o = n._next,
                    !this._paused || m); )
                        (n._active || n._startTime <= this._time && !n._paused && !n._gc) && (n._reversed ? n.render((n._dirty ? n.totalDuration() : n._totalDuration) - (t - n._startTime) * n._timeScale, e, i) : n.render((t - n._startTime) * n._timeScale, e, i)),
                        n = o;
                else
                    for (n = this._last; n && (o = n._prev,
                    !this._paused || m); )
                        (n._active || h >= n._startTime && !n._paused && !n._gc) && (n._reversed ? n.render((n._dirty ? n.totalDuration() : n._totalDuration) - (t - n._startTime) * n._timeScale, e, i) : n.render((t - n._startTime) * n._timeScale, e, i)),
                        n = o;
                this._onUpdate && (e || (c.length && u(),
                this._callback("onUpdate"))),
                s && (this._gc || (p === this._startTime || f !== this._timeScale) && (0 === this._time || d >= this.totalDuration()) && (r && (c.length && u(),
                this._timeline.autoRemoveChildren && this._enabled(!1, !1),
                this._active = !1),
                !e && this.vars[s] && this._callback(s)))
            }
        }
        ,
        g._hasPausedChild = function() {
            for (var t = this._first; t; ) {
                if (t._paused || t instanceof n && t._hasPausedChild())
                    return !0;
                t = t._next
            }
            return !1
        }
        ,
        g.getChildren = function(t, e, n, a) {
            a = a || -9999999999;
            for (var r = [], o = this._first, s = 0; o; )
                a > o._startTime || (o instanceof i ? e !== !1 && (r[s++] = o) : (n !== !1 && (r[s++] = o),
                t !== !1 && (r = r.concat(o.getChildren(!0, e, n)),
                s = r.length))),
                o = o._next;
            return r
        }
        ,
        g.getTweensOf = function(t, e) {
            var n, a, r = this._gc, o = [], s = 0;
            for (r && this._enabled(!0, !0),
            n = i.getTweensOf(t),
            a = n.length; --a > -1; )
                (n[a].timeline === this || e && this._contains(n[a])) && (o[s++] = n[a]);
            return r && this._enabled(!1, !0),
            o
        }
        ,
        g.recent = function() {
            return this._recent
        }
        ,
        g._contains = function(t) {
            for (var e = t.timeline; e; ) {
                if (e === this)
                    return !0;
                e = e.timeline
            }
            return !1
        }
        ,
        g.shiftChildren = function(t, e, i) {
            i = i || 0;
            for (var n, a = this._first, r = this._labels; a; )
                a._startTime >= i && (a._startTime += t),
                a = a._next;
            if (e)
                for (n in r)
                    r[n] >= i && (r[n] += t);
            return this._uncache(!0)
        }
        ,
        g._kill = function(t, e) {
            if (!t && !e)
                return this._enabled(!1, !1);
            for (var i = e ? this.getTweensOf(e) : this.getChildren(!0, !0, !1), n = i.length, a = !1; --n > -1; )
                i[n]._kill(t, e) && (a = !0);
            return a
        }
        ,
        g.clear = function(t) {
            var e = this.getChildren(!1, !0, !0)
              , i = e.length;
            for (this._time = this._totalTime = 0; --i > -1; )
                e[i]._enabled(!1, !1);
            return t !== !1 && (this._labels = {}),
            this._uncache(!0)
        }
        ,
        g.invalidate = function() {
            for (var e = this._first; e; )
                e.invalidate(),
                e = e._next;
            return t.prototype.invalidate.call(this)
        }
        ,
        g._enabled = function(t, i) {
            if (t === this._gc)
                for (var n = this._first; n; )
                    n._enabled(t, !0),
                    n = n._next;
            return e.prototype._enabled.call(this, t, i)
        }
        ,
        g.totalTime = function() {
            this._forcingPlayhead = !0;
            var e = t.prototype.totalTime.apply(this, arguments);
            return this._forcingPlayhead = !1,
            e
        }
        ,
        g.duration = function(t) {
            return arguments.length ? (0 !== this.duration() && 0 !== t && this.timeScale(this._duration / t),
            this) : (this._dirty && this.totalDuration(),
            this._duration)
        }
        ,
        g.totalDuration = function(t) {
            if (!arguments.length) {
                if (this._dirty) {
                    for (var e, i, n = 0, a = this._last, r = 999999999999; a; )
                        e = a._prev,
                        a._dirty && a.totalDuration(),
                        a._startTime > r && this._sortChildren && !a._paused ? this.add(a, a._startTime - a._delay) : r = a._startTime,
                        0 > a._startTime && !a._paused && (n -= a._startTime,
                        this._timeline.smoothChildTiming && (this._startTime += a._startTime / this._timeScale),
                        this.shiftChildren(-a._startTime, !1, -9999999999),
                        r = 0),
                        i = a._startTime + a._totalDuration / a._timeScale,
                        i > n && (n = i),
                        a = e;
                    this._duration = this._totalDuration = n,
                    this._dirty = !1
                }
                return this._totalDuration
            }
            return 0 !== this.totalDuration() && 0 !== t && this.timeScale(this._totalDuration / t),
            this
        }
        ,
        g.paused = function(e) {
            if (!e)
                for (var i = this._first, n = this._time; i; )
                    i._startTime === n && "isPause" === i.data && (i._rawPrevTime = 0),
                    i = i._next;
            return t.prototype.paused.apply(this, arguments)
        }
        ,
        g.usesFrames = function() {
            for (var e = this._timeline; e._timeline; )
                e = e._timeline;
            return e === t._rootFramesTimeline
        }
        ,
        g.rawTime = function() {
            return this._paused ? this._totalTime : (this._timeline.rawTime() - this._startTime) * this._timeScale
        }
        ,
        n
    }, !0),
    _gsScope._gsDefine("TimelineMax", ["TimelineLite", "TweenLite", "easing.Ease"], function(t, e, i) {
        var n = function(e) {
            t.call(this, e),
            this._repeat = this.vars.repeat || 0,
            this._repeatDelay = this.vars.repeatDelay || 0,
            this._cycle = 0,
            this._yoyo = this.vars.yoyo === !0,
            this._dirty = !0
        }
          , a = 1e-10
          , r = e._internals
          , o = r.lazyTweens
          , s = r.lazyRender
          , l = new i(null,null,1,0)
          , c = n.prototype = new t;
        return c.constructor = n,
        c.kill()._gc = !1,
        n.version = "1.17.0",
        c.invalidate = function() {
            return this._yoyo = this.vars.yoyo === !0,
            this._repeat = this.vars.repeat || 0,
            this._repeatDelay = this.vars.repeatDelay || 0,
            this._uncache(!0),
            t.prototype.invalidate.call(this)
        }
        ,
        c.addCallback = function(t, i, n, a) {
            return this.add(e.delayedCall(0, t, n, a), i)
        }
        ,
        c.removeCallback = function(t, e) {
            if (t)
                if (null == e)
                    this._kill(null, t);
                else
                    for (var i = this.getTweensOf(t, !1), n = i.length, a = this._parseTimeOrLabel(e); --n > -1; )
                        i[n]._startTime === a && i[n]._enabled(!1, !1);
            return this
        }
        ,
        c.removePause = function(e) {
            return this.removeCallback(t._internals.pauseCallback, e)
        }
        ,
        c.tweenTo = function(t, i) {
            i = i || {};
            var n, a, r, o = {
                ease: l,
                useFrames: this.usesFrames(),
                immediateRender: !1
            };
            for (a in i)
                o[a] = i[a];
            return o.time = this._parseTimeOrLabel(t),
            n = Math.abs(Number(o.time) - this._time) / this._timeScale || .001,
            r = new e(this,n,o),
            o.onStart = function() {
                r.target.paused(!0),
                r.vars.time !== r.target.time() && n === r.duration() && r.duration(Math.abs(r.vars.time - r.target.time()) / r.target._timeScale),
                i.onStart && r._callback("onStart")
            }
            ,
            r
        }
        ,
        c.tweenFromTo = function(t, e, i) {
            i = i || {},
            t = this._parseTimeOrLabel(t),
            i.startAt = {
                onComplete: this.seek,
                onCompleteParams: [t],
                callbackScope: this
            },
            i.immediateRender = i.immediateRender !== !1;
            var n = this.tweenTo(e, i);
            return n.duration(Math.abs(n.vars.time - t) / this._timeScale || .001)
        }
        ,
        c.render = function(t, e, i) {
            this._gc && this._enabled(!0, !1);
            var n, r, l, c, u, d, h = this._dirty ? this.totalDuration() : this._totalDuration, p = this._duration, f = this._time, m = this._totalTime, g = this._startTime, v = this._timeScale, _ = this._rawPrevTime, y = this._paused, b = this._cycle;
            if (t >= h)
                this._locked || (this._totalTime = h,
                this._cycle = this._repeat),
                this._reversed || this._hasPausedChild() || (r = !0,
                c = "onComplete",
                u = !!this._timeline.autoRemoveChildren,
                0 === this._duration && (0 === t || 0 > _ || _ === a) && _ !== t && this._first && (u = !0,
                _ > a && (c = "onReverseComplete"))),
                this._rawPrevTime = this._duration || !e || t || this._rawPrevTime === t ? t : a,
                this._yoyo && 0 !== (1 & this._cycle) ? this._time = t = 0 : (this._time = p,
                t = p + 1e-4);
            else if (1e-7 > t)
                if (this._locked || (this._totalTime = this._cycle = 0),
                this._time = 0,
                (0 !== f || 0 === p && _ !== a && (_ > 0 || 0 > t && _ >= 0) && !this._locked) && (c = "onReverseComplete",
                r = this._reversed),
                0 > t)
                    this._active = !1,
                    this._timeline.autoRemoveChildren && this._reversed ? (u = r = !0,
                    c = "onReverseComplete") : _ >= 0 && this._first && (u = !0),
                    this._rawPrevTime = t;
                else {
                    if (this._rawPrevTime = p || !e || t || this._rawPrevTime === t ? t : a,
                    0 === t && r)
                        for (n = this._first; n && 0 === n._startTime; )
                            n._duration || (r = !1),
                            n = n._next;
                    t = 0,
                    this._initted || (u = !0)
                }
            else
                0 === p && 0 > _ && (u = !0),
                this._time = this._rawPrevTime = t,
                this._locked || (this._totalTime = t,
                0 !== this._repeat && (d = p + this._repeatDelay,
                this._cycle = this._totalTime / d >> 0,
                0 !== this._cycle && this._cycle === this._totalTime / d && this._cycle--,
                this._time = this._totalTime - this._cycle * d,
                this._yoyo && 0 !== (1 & this._cycle) && (this._time = p - this._time),
                this._time > p ? (this._time = p,
                t = p + 1e-4) : 0 > this._time ? this._time = t = 0 : t = this._time));
            if (this._cycle !== b && !this._locked) {
                var x = this._yoyo && 0 !== (1 & b)
                  , w = x === (this._yoyo && 0 !== (1 & this._cycle))
                  , T = this._totalTime
                  , C = this._cycle
                  , S = this._rawPrevTime
                  , k = this._time;
                if (this._totalTime = b * p,
                b > this._cycle ? x = !x : this._totalTime += p,
                this._time = f,
                this._rawPrevTime = 0 === p ? _ - 1e-4 : _,
                this._cycle = b,
                this._locked = !0,
                f = x ? 0 : p,
                this.render(f, e, 0 === p),
                e || this._gc || this.vars.onRepeat && this._callback("onRepeat"),
                w && (f = x ? p + 1e-4 : -1e-4,
                this.render(f, !0, !1)),
                this._locked = !1,
                this._paused && !y)
                    return;
                this._time = k,
                this._totalTime = T,
                this._cycle = C,
                this._rawPrevTime = S
            }
            if (!(this._time !== f && this._first || i || u))
                return void (m !== this._totalTime && this._onUpdate && (e || this._callback("onUpdate")));
            if (this._initted || (this._initted = !0),
            this._active || !this._paused && this._totalTime !== m && t > 0 && (this._active = !0),
            0 === m && this.vars.onStart && 0 !== this._totalTime && (e || this._callback("onStart")),
            this._time >= f)
                for (n = this._first; n && (l = n._next,
                !this._paused || y); )
                    (n._active || n._startTime <= this._time && !n._paused && !n._gc) && (n._reversed ? n.render((n._dirty ? n.totalDuration() : n._totalDuration) - (t - n._startTime) * n._timeScale, e, i) : n.render((t - n._startTime) * n._timeScale, e, i)),
                    n = l;
            else
                for (n = this._last; n && (l = n._prev,
                !this._paused || y); )
                    (n._active || f >= n._startTime && !n._paused && !n._gc) && (n._reversed ? n.render((n._dirty ? n.totalDuration() : n._totalDuration) - (t - n._startTime) * n._timeScale, e, i) : n.render((t - n._startTime) * n._timeScale, e, i)),
                    n = l;
            this._onUpdate && (e || (o.length && s(),
            this._callback("onUpdate"))),
            c && (this._locked || this._gc || (g === this._startTime || v !== this._timeScale) && (0 === this._time || h >= this.totalDuration()) && (r && (o.length && s(),
            this._timeline.autoRemoveChildren && this._enabled(!1, !1),
            this._active = !1),
            !e && this.vars[c] && this._callback(c)))
        }
        ,
        c.getActive = function(t, e, i) {
            null == t && (t = !0),
            null == e && (e = !0),
            null == i && (i = !1);
            var n, a, r = [], o = this.getChildren(t, e, i), s = 0, l = o.length;
            for (n = 0; l > n; n++)
                a = o[n],
                a.isActive() && (r[s++] = a);
            return r
        }
        ,
        c.getLabelAfter = function(t) {
            t || 0 !== t && (t = this._time);
            var e, i = this.getLabelsArray(), n = i.length;
            for (e = 0; n > e; e++)
                if (i[e].time > t)
                    return i[e].name;
            return null
        }
        ,
        c.getLabelBefore = function(t) {
            null == t && (t = this._time);
            for (var e = this.getLabelsArray(), i = e.length; --i > -1; )
                if (t > e[i].time)
                    return e[i].name;
            return null
        }
        ,
        c.getLabelsArray = function() {
            var t, e = [], i = 0;
            for (t in this._labels)
                e[i++] = {
                    time: this._labels[t],
                    name: t
                };
            return e.sort(function(t, e) {
                return t.time - e.time
            }),
            e
        }
        ,
        c.progress = function(t, e) {
            return arguments.length ? this.totalTime(this.duration() * (this._yoyo && 0 !== (1 & this._cycle) ? 1 - t : t) + this._cycle * (this._duration + this._repeatDelay), e) : this._time / this.duration()
        }
        ,
        c.totalProgress = function(t, e) {
            return arguments.length ? this.totalTime(this.totalDuration() * t, e) : this._totalTime / this.totalDuration()
        }
        ,
        c.totalDuration = function(e) {
            return arguments.length ? -1 === this._repeat ? this : this.duration((e - this._repeat * this._repeatDelay) / (this._repeat + 1)) : (this._dirty && (t.prototype.totalDuration.call(this),
            this._totalDuration = -1 === this._repeat ? 999999999999 : this._duration * (this._repeat + 1) + this._repeatDelay * this._repeat),
            this._totalDuration)
        }
        ,
        c.time = function(t, e) {
            return arguments.length ? (this._dirty && this.totalDuration(),
            t > this._duration && (t = this._duration),
            this._yoyo && 0 !== (1 & this._cycle) ? t = this._duration - t + this._cycle * (this._duration + this._repeatDelay) : 0 !== this._repeat && (t += this._cycle * (this._duration + this._repeatDelay)),
            this.totalTime(t, e)) : this._time
        }
        ,
        c.repeat = function(t) {
            return arguments.length ? (this._repeat = t,
            this._uncache(!0)) : this._repeat
        }
        ,
        c.repeatDelay = function(t) {
            return arguments.length ? (this._repeatDelay = t,
            this._uncache(!0)) : this._repeatDelay
        }
        ,
        c.yoyo = function(t) {
            return arguments.length ? (this._yoyo = t,
            this) : this._yoyo
        }
        ,
        c.currentLabel = function(t) {
            return arguments.length ? this.seek(t, !0) : this.getLabelBefore(this._time + 1e-8)
        }
        ,
        n
    }, !0),
    function() {
        var t = 180 / Math.PI
          , e = []
          , i = []
          , n = []
          , a = {}
          , r = _gsScope._gsDefine.globals
          , o = function(t, e, i, n) {
            this.a = t,
            this.b = e,
            this.c = i,
            this.d = n,
            this.da = n - t,
            this.ca = i - t,
            this.ba = e - t
        }
          , s = ",x,y,z,left,top,right,bottom,marginTop,marginLeft,marginRight,marginBottom,paddingLeft,paddingTop,paddingRight,paddingBottom,backgroundPosition,backgroundPosition_y,"
          , l = function(t, e, i, n) {
            var a = {
                a: t
            }
              , r = {}
              , o = {}
              , s = {
                c: n
            }
              , l = (t + e) / 2
              , c = (e + i) / 2
              , u = (i + n) / 2
              , d = (l + c) / 2
              , h = (c + u) / 2
              , p = (h - d) / 8;
            return a.b = l + (t - l) / 4,
            r.b = d + p,
            a.c = r.a = (a.b + r.b) / 2,
            r.c = o.a = (d + h) / 2,
            o.b = h - p,
            s.b = u + (n - u) / 4,
            o.c = s.a = (o.b + s.b) / 2,
            [a, r, o, s]
        }
          , c = function(t, a, r, o, s) {
            var c, u, d, h, p, f, m, g, v, _, y, b, x, w = t.length - 1, T = 0, C = t[0].a;
            for (c = 0; w > c; c++)
                p = t[T],
                u = p.a,
                d = p.d,
                h = t[T + 1].d,
                s ? (y = e[c],
                b = i[c],
                x = .25 * (b + y) * a / (o ? .5 : n[c] || .5),
                f = d - (d - u) * (o ? .5 * a : 0 !== y ? x / y : 0),
                m = d + (h - d) * (o ? .5 * a : 0 !== b ? x / b : 0),
                g = d - (f + ((m - f) * (3 * y / (y + b) + .5) / 4 || 0))) : (f = d - .5 * (d - u) * a,
                m = d + .5 * (h - d) * a,
                g = d - (f + m) / 2),
                f += g,
                m += g,
                p.c = v = f,
                p.b = 0 !== c ? C : C = p.a + .6 * (p.c - p.a),
                p.da = d - u,
                p.ca = v - u,
                p.ba = C - u,
                r ? (_ = l(u, C, v, d),
                t.splice(T, 1, _[0], _[1], _[2], _[3]),
                T += 4) : T++,
                C = m;
            p = t[T],
            p.b = C,
            p.c = C + .4 * (p.d - C),
            p.da = p.d - p.a,
            p.ca = p.c - p.a,
            p.ba = C - p.a,
            r && (_ = l(p.a, C, p.c, p.d),
            t.splice(T, 1, _[0], _[1], _[2], _[3]))
        }
          , u = function(t, n, a, r) {
            var s, l, c, u, d, h, p = [];
            if (r)
                for (t = [r].concat(t),
                l = t.length; --l > -1; )
                    "string" == typeof (h = t[l][n]) && "=" === h.charAt(1) && (t[l][n] = r[n] + Number(h.charAt(0) + h.substr(2)));
            if (s = t.length - 2,
            0 > s)
                return p[0] = new o(t[0][n],0,0,t[-1 > s ? 0 : 1][n]),
                p;
            for (l = 0; s > l; l++)
                c = t[l][n],
                u = t[l + 1][n],
                p[l] = new o(c,0,0,u),
                a && (d = t[l + 2][n],
                e[l] = (e[l] || 0) + (u - c) * (u - c),
                i[l] = (i[l] || 0) + (d - u) * (d - u));
            return p[l] = new o(t[l][n],0,0,t[l + 1][n]),
            p
        }
          , d = function(t, r, o, l, d, h) {
            var p, f, m, g, v, _, y, b, x = {}, w = [], T = h || t[0];
            d = "string" == typeof d ? "," + d + "," : s,
            null == r && (r = 1);
            for (f in t[0])
                w.push(f);
            if (t.length > 1) {
                for (b = t[t.length - 1],
                y = !0,
                p = w.length; --p > -1; )
                    if (f = w[p],
                    Math.abs(T[f] - b[f]) > .05) {
                        y = !1;
                        break
                    }
                y && (t = t.concat(),
                h && t.unshift(h),
                t.push(t[1]),
                h = t[t.length - 3])
            }
            for (e.length = i.length = n.length = 0,
            p = w.length; --p > -1; )
                f = w[p],
                a[f] = -1 !== d.indexOf("," + f + ","),
                x[f] = u(t, f, a[f], h);
            for (p = e.length; --p > -1; )
                e[p] = Math.sqrt(e[p]),
                i[p] = Math.sqrt(i[p]);
            if (!l) {
                for (p = w.length; --p > -1; )
                    if (a[f])
                        for (m = x[w[p]],
                        _ = m.length - 1,
                        g = 0; _ > g; g++)
                            v = m[g + 1].da / i[g] + m[g].da / e[g],
                            n[g] = (n[g] || 0) + v * v;
                for (p = n.length; --p > -1; )
                    n[p] = Math.sqrt(n[p])
            }
            for (p = w.length,
            g = o ? 4 : 1; --p > -1; )
                f = w[p],
                m = x[f],
                c(m, r, o, l, a[f]),
                y && (m.splice(0, g),
                m.splice(m.length - g, g));
            return x
        }
          , h = function(t, e, i) {
            e = e || "soft";
            var n, a, r, s, l, c, u, d, h, p, f, m = {}, g = "cubic" === e ? 3 : 2, v = "soft" === e, _ = [];
            if (v && i && (t = [i].concat(t)),
            null == t || g + 1 > t.length)
                throw "invalid Bezier data";
            for (h in t[0])
                _.push(h);
            for (c = _.length; --c > -1; ) {
                for (h = _[c],
                m[h] = l = [],
                p = 0,
                d = t.length,
                u = 0; d > u; u++)
                    n = null == i ? t[u][h] : "string" == typeof (f = t[u][h]) && "=" === f.charAt(1) ? i[h] + Number(f.charAt(0) + f.substr(2)) : Number(f),
                    v && u > 1 && d - 1 > u && (l[p++] = (n + l[p - 2]) / 2),
                    l[p++] = n;
                for (d = p - g + 1,
                p = 0,
                u = 0; d > u; u += g)
                    n = l[u],
                    a = l[u + 1],
                    r = l[u + 2],
                    s = 2 === g ? 0 : l[u + 3],
                    l[p++] = f = 3 === g ? new o(n,a,r,s) : new o(n,(2 * a + n) / 3,(2 * a + r) / 3,r);
                l.length = p
            }
            return m
        }
          , p = function(t, e, i) {
            for (var n, a, r, o, s, l, c, u, d, h, p, f = 1 / i, m = t.length; --m > -1; )
                for (h = t[m],
                r = h.a,
                o = h.d - r,
                s = h.c - r,
                l = h.b - r,
                n = a = 0,
                u = 1; i >= u; u++)
                    c = f * u,
                    d = 1 - c,
                    n = a - (a = (c * c * o + 3 * d * (c * s + d * l)) * c),
                    p = m * i + u - 1,
                    e[p] = (e[p] || 0) + n * n
        }
          , f = function(t, e) {
            e = e >> 0 || 6;
            var i, n, a, r, o = [], s = [], l = 0, c = 0, u = e - 1, d = [], h = [];
            for (i in t)
                p(t[i], o, e);
            for (a = o.length,
            n = 0; a > n; n++)
                l += Math.sqrt(o[n]),
                r = n % e,
                h[r] = l,
                r === u && (c += l,
                r = n / e >> 0,
                d[r] = h,
                s[r] = c,
                l = 0,
                h = []);
            return {
                length: c,
                lengths: s,
                segments: d
            }
        }
          , m = _gsScope._gsDefine.plugin({
            propName: "bezier",
            priority: -1,
            version: "1.3.4",
            API: 2,
            global: !0,
            init: function(t, e, i) {
                this._target = t,
                e instanceof Array && (e = {
                    values: e
                }),
                this._func = {},
                this._round = {},
                this._props = [],
                this._timeRes = null == e.timeResolution ? 6 : parseInt(e.timeResolution, 10);
                var n, a, r, o, s, l = e.values || [], c = {}, u = l[0], p = e.autoRotate || i.vars.orientToBezier;
                this._autoRotate = p ? p instanceof Array ? p : [["x", "y", "rotation", p === !0 ? 0 : Number(p) || 0]] : null;
                for (n in u)
                    this._props.push(n);
                for (r = this._props.length; --r > -1; )
                    n = this._props[r],
                    this._overwriteProps.push(n),
                    a = this._func[n] = "function" == typeof t[n],
                    c[n] = a ? t[n.indexOf("set") || "function" != typeof t["get" + n.substr(3)] ? n : "get" + n.substr(3)]() : parseFloat(t[n]),
                    s || c[n] !== l[0][n] && (s = c);
                if (this._beziers = "cubic" !== e.type && "quadratic" !== e.type && "soft" !== e.type ? d(l, isNaN(e.curviness) ? 1 : e.curviness, !1, "thruBasic" === e.type, e.correlate, s) : h(l, e.type, c),
                this._segCount = this._beziers[n].length,
                this._timeRes) {
                    var m = f(this._beziers, this._timeRes);
                    this._length = m.length,
                    this._lengths = m.lengths,
                    this._segments = m.segments,
                    this._l1 = this._li = this._s1 = this._si = 0,
                    this._l2 = this._lengths[0],
                    this._curSeg = this._segments[0],
                    this._s2 = this._curSeg[0],
                    this._prec = 1 / this._curSeg.length
                }
                if (p = this._autoRotate)
                    for (this._initialRotations = [],
                    p[0]instanceof Array || (this._autoRotate = p = [p]),
                    r = p.length; --r > -1; ) {
                        for (o = 0; 3 > o; o++)
                            n = p[r][o],
                            this._func[n] = "function" == typeof t[n] ? t[n.indexOf("set") || "function" != typeof t["get" + n.substr(3)] ? n : "get" + n.substr(3)] : !1;
                        n = p[r][2],
                        this._initialRotations[r] = this._func[n] ? this._func[n].call(this._target) : this._target[n]
                    }
                return this._startRatio = i.vars.runBackwards ? 1 : 0,
                !0
            },
            set: function(e) {
                var i, n, a, r, o, s, l, c, u, d, h = this._segCount, p = this._func, f = this._target, m = e !== this._startRatio;
                if (this._timeRes) {
                    if (u = this._lengths,
                    d = this._curSeg,
                    e *= this._length,
                    a = this._li,
                    e > this._l2 && h - 1 > a) {
                        for (c = h - 1; c > a && e >= (this._l2 = u[++a]); )
                            ;
                        this._l1 = u[a - 1],
                        this._li = a,
                        this._curSeg = d = this._segments[a],
                        this._s2 = d[this._s1 = this._si = 0]
                    } else if (this._l1 > e && a > 0) {
                        for (; a > 0 && (this._l1 = u[--a]) >= e; )
                            ;
                        0 === a && this._l1 > e ? this._l1 = 0 : a++,
                        this._l2 = u[a],
                        this._li = a,
                        this._curSeg = d = this._segments[a],
                        this._s1 = d[(this._si = d.length - 1) - 1] || 0,
                        this._s2 = d[this._si]
                    }
                    if (i = a,
                    e -= this._l1,
                    a = this._si,
                    e > this._s2 && d.length - 1 > a) {
                        for (c = d.length - 1; c > a && e >= (this._s2 = d[++a]); )
                            ;
                        this._s1 = d[a - 1],
                        this._si = a
                    } else if (this._s1 > e && a > 0) {
                        for (; a > 0 && (this._s1 = d[--a]) >= e; )
                            ;
                        0 === a && this._s1 > e ? this._s1 = 0 : a++,
                        this._s2 = d[a],
                        this._si = a
                    }
                    s = (a + (e - this._s1) / (this._s2 - this._s1)) * this._prec
                } else
                    i = 0 > e ? 0 : e >= 1 ? h - 1 : h * e >> 0,
                    s = (e - i * (1 / h)) * h;
                for (n = 1 - s,
                a = this._props.length; --a > -1; )
                    r = this._props[a],
                    o = this._beziers[r][i],
                    l = (s * s * o.da + 3 * n * (s * o.ca + n * o.ba)) * s + o.a,
                    this._round[r] && (l = Math.round(l)),
                    p[r] ? f[r](l) : f[r] = l;
                if (this._autoRotate) {
                    var g, v, _, y, b, x, w, T = this._autoRotate;
                    for (a = T.length; --a > -1; )
                        r = T[a][2],
                        x = T[a][3] || 0,
                        w = T[a][4] === !0 ? 1 : t,
                        o = this._beziers[T[a][0]],
                        g = this._beziers[T[a][1]],
                        o && g && (o = o[i],
                        g = g[i],
                        v = o.a + (o.b - o.a) * s,
                        y = o.b + (o.c - o.b) * s,
                        v += (y - v) * s,
                        y += (o.c + (o.d - o.c) * s - y) * s,
                        _ = g.a + (g.b - g.a) * s,
                        b = g.b + (g.c - g.b) * s,
                        _ += (b - _) * s,
                        b += (g.c + (g.d - g.c) * s - b) * s,
                        l = m ? Math.atan2(b - _, y - v) * w + x : this._initialRotations[a],
                        p[r] ? f[r](l) : f[r] = l)
                }
            }
        })
          , g = m.prototype;
        m.bezierThrough = d,
        m.cubicToQuadratic = l,
        m._autoCSS = !0,
        m.quadraticToCubic = function(t, e, i) {
            return new o(t,(2 * e + t) / 3,(2 * e + i) / 3,i)
        }
        ,
        m._cssRegister = function() {
            var t = r.CSSPlugin;
            if (t) {
                var e = t._internals
                  , i = e._parseToProxy
                  , n = e._setPluginRatio
                  , a = e.CSSPropTween;
                e._registerComplexSpecialProp("bezier", {
                    parser: function(t, e, r, o, s, l) {
                        e instanceof Array && (e = {
                            values: e
                        }),
                        l = new m;
                        var c, u, d, h = e.values, p = h.length - 1, f = [], g = {};
                        if (0 > p)
                            return s;
                        for (c = 0; p >= c; c++)
                            d = i(t, h[c], o, s, l, p !== c),
                            f[c] = d.end;
                        for (u in e)
                            g[u] = e[u];
                        return g.values = f,
                        s = new a(t,"bezier",0,0,d.pt,2),
                        s.data = d,
                        s.plugin = l,
                        s.setRatio = n,
                        0 === g.autoRotate && (g.autoRotate = !0),
                        !g.autoRotate || g.autoRotate instanceof Array || (c = g.autoRotate === !0 ? 0 : Number(g.autoRotate),
                        g.autoRotate = null != d.end.left ? [["left", "top", "rotation", c, !1]] : null != d.end.x ? [["x", "y", "rotation", c, !1]] : !1),
                        g.autoRotate && (o._transform || o._enableTransforms(!1),
                        d.autoRotate = o._target._gsTransform),
                        l._onInitTween(d.proxy, g, o._tween),
                        s
                    }
                })
            }
        }
        ,
        g._roundProps = function(t, e) {
            for (var i = this._overwriteProps, n = i.length; --n > -1; )
                (t[i[n]] || t.bezier || t.bezierThrough) && (this._round[i[n]] = e)
        }
        ,
        g._kill = function(t) {
            var e, i, n = this._props;
            for (e in this._beziers)
                if (e in t)
                    for (delete this._beziers[e],
                    delete this._func[e],
                    i = n.length; --i > -1; )
                        n[i] === e && n.splice(i, 1);
            return this._super._kill.call(this, t)
        }
    }(),
    _gsScope._gsDefine("plugins.CSSPlugin", ["plugins.TweenPlugin", "TweenLite"], function(t, e) {
        var i, n, a, r, o = function() {
            t.call(this, "css"),
            this._overwriteProps.length = 0,
            this.setRatio = o.prototype.setRatio
        }, s = _gsScope._gsDefine.globals, l = {}, c = o.prototype = new t("css");
        c.constructor = o,
        o.version = "1.17.0",
        o.API = 2,
        o.defaultTransformPerspective = 0,
        o.defaultSkewType = "compensated",
        o.defaultSmoothOrigin = !0,
        c = "px",
        o.suffixMap = {
            top: c,
            right: c,
            bottom: c,
            left: c,
            width: c,
            height: c,
            fontSize: c,
            padding: c,
            margin: c,
            perspective: c,
            lineHeight: ""
        };
        var u, d, h, p, f, m, g = /(?:\d|\-\d|\.\d|\-\.\d)+/g, v = /(?:\d|\-\d|\.\d|\-\.\d|\+=\d|\-=\d|\+=.\d|\-=\.\d)+/g, _ = /(?:\+=|\-=|\-|\b)[\d\-\.]+[a-zA-Z0-9]*(?:%|\b)/gi, y = /(?![+-]?\d*\.?\d+|[+-]|e[+-]\d+)[^0-9]/g, b = /(?:\d|\-|\+|=|#|\.)*/g, x = /opacity *= *([^)]*)/i, w = /opacity:([^;]*)/i, T = /alpha\(opacity *=.+?\)/i, C = /^(rgb|hsl)/, S = /([A-Z])/g, k = /-([a-z])/gi, $ = /(^(?:url\(\"|url\())|(?:(\"\))$|\)$)/gi, D = function(t, e) {
            return e.toUpperCase()
        }, A = /(?:Left|Right|Width)/i, O = /(M11|M12|M21|M22)=[\d\-\.e]+/gi, E = /progid\:DXImageTransform\.Microsoft\.Matrix\(.+?\)/i, P = /,(?=[^\)]*(?:\(|$))/gi, N = Math.PI / 180, M = 180 / Math.PI, R = {}, I = document, B = function(t) {
            return I.createElementNS ? I.createElementNS("http://www.w3.org/1999/xhtml", t) : I.createElement(t)
        }, L = B("div"), j = B("img"), F = o._internals = {
            _specialProps: l
        }, U = navigator.userAgent, z = function() {
            var t = U.indexOf("Android")
              , e = B("a");
            return h = -1 !== U.indexOf("Safari") && -1 === U.indexOf("Chrome") && (-1 === t || Number(U.substr(t + 8, 1)) > 3),
            f = h && 6 > Number(U.substr(U.indexOf("Version/") + 8, 1)),
            p = -1 !== U.indexOf("Firefox"),
            (/MSIE ([0-9]{1,}[\.0-9]{0,})/.exec(U) || /Trident\/.*rv:([0-9]{1,}[\.0-9]{0,})/.exec(U)) && (m = parseFloat(RegExp.$1)),
            e ? (e.style.cssText = "top:1px;opacity:.55;",
            /^0.55/.test(e.style.opacity)) : !1
        }(), H = function(t) {
            return x.test("string" == typeof t ? t : (t.currentStyle ? t.currentStyle.filter : t.style.filter) || "") ? parseFloat(RegExp.$1) / 100 : 1
        }, W = function(t) {
            window.console && console.log(t)
        }, q = "", Y = "", X = function(t, e) {
            e = e || L;
            var i, n, a = e.style;
            if (void 0 !== a[t])
                return t;
            for (t = t.charAt(0).toUpperCase() + t.substr(1),
            i = ["O", "Moz", "ms", "Ms", "Webkit"],
            n = 5; --n > -1 && void 0 === a[i[n] + t]; )
                ;
            return n >= 0 ? (Y = 3 === n ? "ms" : i[n],
            q = "-" + Y.toLowerCase() + "-",
            Y + t) : null
        }, V = I.defaultView ? I.defaultView.getComputedStyle : function() {}
        , G = o.getStyle = function(t, e, i, n, a) {
            var r;
            return z || "opacity" !== e ? (!n && t.style[e] ? r = t.style[e] : (i = i || V(t)) ? r = i[e] || i.getPropertyValue(e) || i.getPropertyValue(e.replace(S, "-$1").toLowerCase()) : t.currentStyle && (r = t.currentStyle[e]),
            null == a || r && "none" !== r && "auto" !== r && "auto auto" !== r ? r : a) : H(t)
        }
        , Q = F.convertToPixels = function(t, i, n, a, r) {
            if ("px" === a || !a)
                return n;
            if ("auto" === a || !n)
                return 0;
            var s, l, c, u = A.test(i), d = t, h = L.style, p = 0 > n;
            if (p && (n = -n),
            "%" === a && -1 !== i.indexOf("border"))
                s = n / 100 * (u ? t.clientWidth : t.clientHeight);
            else {
                if (h.cssText = "border:0 solid red;position:" + G(t, "position") + ";line-height:0;",
                "%" !== a && d.appendChild)
                    h[u ? "borderLeftWidth" : "borderTopWidth"] = n + a;
                else {
                    if (d = t.parentNode || I.body,
                    l = d._gsCache,
                    c = e.ticker.frame,
                    l && u && l.time === c)
                        return l.width * n / 100;
                    h[u ? "width" : "height"] = n + a
                }
                d.appendChild(L),
                s = parseFloat(L[u ? "offsetWidth" : "offsetHeight"]),
                d.removeChild(L),
                u && "%" === a && o.cacheWidths !== !1 && (l = d._gsCache = d._gsCache || {},
                l.time = c,
                l.width = 100 * (s / n)),
                0 !== s || r || (s = Q(t, i, n, a, !0))
            }
            return p ? -s : s
        }
        , J = F.calculateOffset = function(t, e, i) {
            if ("absolute" !== G(t, "position", i))
                return 0;
            var n = "left" === e ? "Left" : "Top"
              , a = G(t, "margin" + n, i);
            return t["offset" + n] - (Q(t, e, parseFloat(a), a.replace(b, "")) || 0)
        }
        , K = function(t, e) {
            var i, n, a, r = {};
            if (e = e || V(t, null))
                if (i = e.length)
                    for (; --i > -1; )
                        a = e[i],
                        (-1 === a.indexOf("-transform") || Ce === a) && (r[a.replace(k, D)] = e.getPropertyValue(a));
                else
                    for (i in e)
                        (-1 === i.indexOf("Transform") || Te === i) && (r[i] = e[i]);
            else if (e = t.currentStyle || t.style)
                for (i in e)
                    "string" == typeof i && void 0 === r[i] && (r[i.replace(k, D)] = e[i]);
            return z || (r.opacity = H(t)),
            n = Ie(t, e, !1),
            r.rotation = n.rotation,
            r.skewX = n.skewX,
            r.scaleX = n.scaleX,
            r.scaleY = n.scaleY,
            r.x = n.x,
            r.y = n.y,
            ke && (r.z = n.z,
            r.rotationX = n.rotationX,
            r.rotationY = n.rotationY,
            r.scaleZ = n.scaleZ),
            r.filters && delete r.filters,
            r
        }, Z = function(t, e, i, n, a) {
            var r, o, s, l = {}, c = t.style;
            for (o in i)
                "cssText" !== o && "length" !== o && isNaN(o) && (e[o] !== (r = i[o]) || a && a[o]) && -1 === o.indexOf("Origin") && ("number" == typeof r || "string" == typeof r) && (l[o] = "auto" !== r || "left" !== o && "top" !== o ? "" !== r && "auto" !== r && "none" !== r || "string" != typeof e[o] || "" === e[o].replace(y, "") ? r : 0 : J(t, o),
                void 0 !== c[o] && (s = new pe(c,o,c[o],s)));
            if (n)
                for (o in n)
                    "className" !== o && (l[o] = n[o]);
            return {
                difs: l,
                firstMPT: s
            }
        }, te = {
            width: ["Left", "Right"],
            height: ["Top", "Bottom"]
        }, ee = ["marginLeft", "marginRight", "marginTop", "marginBottom"], ie = function(t, e, i) {
            var n = parseFloat("width" === e ? t.offsetWidth : t.offsetHeight)
              , a = te[e]
              , r = a.length;
            for (i = i || V(t, null); --r > -1; )
                n -= parseFloat(G(t, "padding" + a[r], i, !0)) || 0,
                n -= parseFloat(G(t, "border" + a[r] + "Width", i, !0)) || 0;
            return n
        }, ne = function(t, e) {
            (null == t || "" === t || "auto" === t || "auto auto" === t) && (t = "0 0");
            var i = t.split(" ")
              , n = -1 !== t.indexOf("left") ? "0%" : -1 !== t.indexOf("right") ? "100%" : i[0]
              , a = -1 !== t.indexOf("top") ? "0%" : -1 !== t.indexOf("bottom") ? "100%" : i[1];
            return null == a ? a = "center" === n ? "50%" : "0" : "center" === a && (a = "50%"),
            ("center" === n || isNaN(parseFloat(n)) && -1 === (n + "").indexOf("=")) && (n = "50%"),
            t = n + " " + a + (i.length > 2 ? " " + i[2] : ""),
            e && (e.oxp = -1 !== n.indexOf("%"),
            e.oyp = -1 !== a.indexOf("%"),
            e.oxr = "=" === n.charAt(1),
            e.oyr = "=" === a.charAt(1),
            e.ox = parseFloat(n.replace(y, "")),
            e.oy = parseFloat(a.replace(y, "")),
            e.v = t),
            e || t
        }, ae = function(t, e) {
            return "string" == typeof t && "=" === t.charAt(1) ? parseInt(t.charAt(0) + "1", 10) * parseFloat(t.substr(2)) : parseFloat(t) - parseFloat(e)
        }, re = function(t, e) {
            return null == t ? e : "string" == typeof t && "=" === t.charAt(1) ? parseInt(t.charAt(0) + "1", 10) * parseFloat(t.substr(2)) + e : parseFloat(t)
        }, oe = function(t, e, i, n) {
            var a, r, o, s, l, c = 1e-6;
            return null == t ? s = e : "number" == typeof t ? s = t : (a = 360,
            r = t.split("_"),
            l = "=" === t.charAt(1),
            o = (l ? parseInt(t.charAt(0) + "1", 10) * parseFloat(r[0].substr(2)) : parseFloat(r[0])) * (-1 === t.indexOf("rad") ? 1 : M) - (l ? 0 : e),
            r.length && (n && (n[i] = e + o),
            -1 !== t.indexOf("short") && (o %= a,
            o !== o % (a / 2) && (o = 0 > o ? o + a : o - a)),
            -1 !== t.indexOf("_cw") && 0 > o ? o = (o + 9999999999 * a) % a - (0 | o / a) * a : -1 !== t.indexOf("ccw") && o > 0 && (o = (o - 9999999999 * a) % a - (0 | o / a) * a)),
            s = e + o),
            c > s && s > -c && (s = 0),
            s
        }, se = {
            aqua: [0, 255, 255],
            lime: [0, 255, 0],
            silver: [192, 192, 192],
            black: [0, 0, 0],
            maroon: [128, 0, 0],
            teal: [0, 128, 128],
            blue: [0, 0, 255],
            navy: [0, 0, 128],
            white: [255, 255, 255],
            fuchsia: [255, 0, 255],
            olive: [128, 128, 0],
            yellow: [255, 255, 0],
            orange: [255, 165, 0],
            gray: [128, 128, 128],
            purple: [128, 0, 128],
            green: [0, 128, 0],
            red: [255, 0, 0],
            pink: [255, 192, 203],
            cyan: [0, 255, 255],
            transparent: [255, 255, 255, 0]
        }, le = function(t, e, i) {
            return t = 0 > t ? t + 1 : t > 1 ? t - 1 : t,
            0 | 255 * (1 > 6 * t ? e + 6 * (i - e) * t : .5 > t ? i : 2 > 3 * t ? e + 6 * (i - e) * (2 / 3 - t) : e) + .5
        }, ce = o.parseColor = function(t) {
            var e, i, n, a, r, o;
            return t && "" !== t ? "number" == typeof t ? [t >> 16, 255 & t >> 8, 255 & t] : ("," === t.charAt(t.length - 1) && (t = t.substr(0, t.length - 1)),
            se[t] ? se[t] : "#" === t.charAt(0) ? (4 === t.length && (e = t.charAt(1),
            i = t.charAt(2),
            n = t.charAt(3),
            t = "#" + e + e + i + i + n + n),
            t = parseInt(t.substr(1), 16),
            [t >> 16, 255 & t >> 8, 255 & t]) : "hsl" === t.substr(0, 3) ? (t = t.match(g),
            a = Number(t[0]) % 360 / 360,
            r = Number(t[1]) / 100,
            o = Number(t[2]) / 100,
            i = .5 >= o ? o * (r + 1) : o + r - o * r,
            e = 2 * o - i,
            t.length > 3 && (t[3] = Number(t[3])),
            t[0] = le(a + 1 / 3, e, i),
            t[1] = le(a, e, i),
            t[2] = le(a - 1 / 3, e, i),
            t) : (t = t.match(g) || se.transparent,
            t[0] = Number(t[0]),
            t[1] = Number(t[1]),
            t[2] = Number(t[2]),
            t.length > 3 && (t[3] = Number(t[3])),
            t)) : se.black
        }
        , ue = "(?:\\b(?:(?:rgb|rgba|hsl|hsla)\\(.+?\\))|\\B#.+?\\b";
        for (c in se)
            ue += "|" + c + "\\b";
        ue = RegExp(ue + ")", "gi");
        var de = function(t, e, i, n) {
            if (null == t)
                return function(t) {
                    return t
                }
                ;
            var a, r = e ? (t.match(ue) || [""])[0] : "", o = t.split(r).join("").match(_) || [], s = t.substr(0, t.indexOf(o[0])), l = ")" === t.charAt(t.length - 1) ? ")" : "", c = -1 !== t.indexOf(" ") ? " " : ",", u = o.length, d = u > 0 ? o[0].replace(g, "") : "";
            return u ? a = e ? function(t) {
                var e, h, p, f;
                if ("number" == typeof t)
                    t += d;
                else if (n && P.test(t)) {
                    for (f = t.replace(P, "|").split("|"),
                    p = 0; f.length > p; p++)
                        f[p] = a(f[p]);
                    return f.join(",")
                }
                if (e = (t.match(ue) || [r])[0],
                h = t.split(e).join("").match(_) || [],
                p = h.length,
                u > p--)
                    for (; u > ++p; )
                        h[p] = i ? h[0 | (p - 1) / 2] : o[p];
                return s + h.join(c) + c + e + l + (-1 !== t.indexOf("inset") ? " inset" : "")
            }
            : function(t) {
                var e, r, h;
                if ("number" == typeof t)
                    t += d;
                else if (n && P.test(t)) {
                    for (r = t.replace(P, "|").split("|"),
                    h = 0; r.length > h; h++)
                        r[h] = a(r[h]);
                    return r.join(",")
                }
                if (e = t.match(_) || [],
                h = e.length,
                u > h--)
                    for (; u > ++h; )
                        e[h] = i ? e[0 | (h - 1) / 2] : o[h];
                return s + e.join(c) + l
            }
            : function(t) {
                return t
            }
        }
          , he = function(t) {
            return t = t.split(","),
            function(e, i, n, a, r, o, s) {
                var l, c = (i + "").split(" ");
                for (s = {},
                l = 0; 4 > l; l++)
                    s[t[l]] = c[l] = c[l] || c[(l - 1) / 2 >> 0];
                return a.parse(e, s, r, o)
            }
        }
          , pe = (F._setPluginRatio = function(t) {
            this.plugin.setRatio(t);
            for (var e, i, n, a, r = this.data, o = r.proxy, s = r.firstMPT, l = 1e-6; s; )
                e = o[s.v],
                s.r ? e = Math.round(e) : l > e && e > -l && (e = 0),
                s.t[s.p] = e,
                s = s._next;
            if (r.autoRotate && (r.autoRotate.rotation = o.rotation),
            1 === t)
                for (s = r.firstMPT; s; ) {
                    if (i = s.t,
                    i.type) {
                        if (1 === i.type) {
                            for (a = i.xs0 + i.s + i.xs1,
                            n = 1; i.l > n; n++)
                                a += i["xn" + n] + i["xs" + (n + 1)];
                            i.e = a
                        }
                    } else
                        i.e = i.s + i.xs0;
                    s = s._next
                }
        }
        ,
        function(t, e, i, n, a) {
            this.t = t,
            this.p = e,
            this.v = i,
            this.r = a,
            n && (n._prev = this,
            this._next = n)
        }
        )
          , fe = (F._parseToProxy = function(t, e, i, n, a, r) {
            var o, s, l, c, u, d = n, h = {}, p = {}, f = i._transform, m = R;
            for (i._transform = null,
            R = e,
            n = u = i.parse(t, e, n, a),
            R = m,
            r && (i._transform = f,
            d && (d._prev = null,
            d._prev && (d._prev._next = null))); n && n !== d; ) {
                if (1 >= n.type && (s = n.p,
                p[s] = n.s + n.c,
                h[s] = n.s,
                r || (c = new pe(n,"s",s,c,n.r),
                n.c = 0),
                1 === n.type))
                    for (o = n.l; --o > 0; )
                        l = "xn" + o,
                        s = n.p + "_" + l,
                        p[s] = n.data[l],
                        h[s] = n[l],
                        r || (c = new pe(n,l,s,c,n.rxp[l]));
                n = n._next
            }
            return {
                proxy: h,
                end: p,
                firstMPT: c,
                pt: u
            }
        }
        ,
        F.CSSPropTween = function(t, e, n, a, o, s, l, c, u, d, h) {
            this.t = t,
            this.p = e,
            this.s = n,
            this.c = a,
            this.n = l || e,
            t instanceof fe || r.push(this.n),
            this.r = c,
            this.type = s || 0,
            u && (this.pr = u,
            i = !0),
            this.b = void 0 === d ? n : d,
            this.e = void 0 === h ? n + a : h,
            o && (this._next = o,
            o._prev = this)
        }
        )
          , me = function(t, e, i, n, a, r) {
            var o = new fe(t,e,i,n - i,a,-1,r);
            return o.b = i,
            o.e = o.xs0 = n,
            o
        }
          , ge = o.parseComplex = function(t, e, i, n, a, r, o, s, l, c) {
            i = i || r || "",
            o = new fe(t,e,0,0,o,c ? 2 : 1,null,!1,s,i,n),
            n += "";
            var d, h, p, f, m, _, y, b, x, w, T, S, k = i.split(", ").join(",").split(" "), $ = n.split(", ").join(",").split(" "), D = k.length, A = u !== !1;
            for ((-1 !== n.indexOf(",") || -1 !== i.indexOf(",")) && (k = k.join(" ").replace(P, ", ").split(" "),
            $ = $.join(" ").replace(P, ", ").split(" "),
            D = k.length),
            D !== $.length && (k = (r || "").split(" "),
            D = k.length),
            o.plugin = l,
            o.setRatio = c,
            d = 0; D > d; d++)
                if (f = k[d],
                m = $[d],
                b = parseFloat(f),
                b || 0 === b)
                    o.appendXtra("", b, ae(m, b), m.replace(v, ""), A && -1 !== m.indexOf("px"), !0);
                else if (a && ("#" === f.charAt(0) || se[f] || C.test(f)))
                    S = "," === m.charAt(m.length - 1) ? ")," : ")",
                    f = ce(f),
                    m = ce(m),
                    x = f.length + m.length > 6,
                    x && !z && 0 === m[3] ? (o["xs" + o.l] += o.l ? " transparent" : "transparent",
                    o.e = o.e.split($[d]).join("transparent")) : (z || (x = !1),
                    o.appendXtra(x ? "rgba(" : "rgb(", f[0], m[0] - f[0], ",", !0, !0).appendXtra("", f[1], m[1] - f[1], ",", !0).appendXtra("", f[2], m[2] - f[2], x ? "," : S, !0),
                    x && (f = 4 > f.length ? 1 : f[3],
                    o.appendXtra("", f, (4 > m.length ? 1 : m[3]) - f, S, !1)));
                else if (_ = f.match(g)) {
                    if (y = m.match(v),
                    !y || y.length !== _.length)
                        return o;
                    for (p = 0,
                    h = 0; _.length > h; h++)
                        T = _[h],
                        w = f.indexOf(T, p),
                        o.appendXtra(f.substr(p, w - p), Number(T), ae(y[h], T), "", A && "px" === f.substr(w + T.length, 2), 0 === h),
                        p = w + T.length;
                    o["xs" + o.l] += f.substr(p)
                } else
                    o["xs" + o.l] += o.l ? " " + f : f;
            if (-1 !== n.indexOf("=") && o.data) {
                for (S = o.xs0 + o.data.s,
                d = 1; o.l > d; d++)
                    S += o["xs" + d] + o.data["xn" + d];
                o.e = S + o["xs" + d]
            }
            return o.l || (o.type = -1,
            o.xs0 = o.e),
            o.xfirst || o
        }
          , ve = 9;
        for (c = fe.prototype,
        c.l = c.pr = 0; --ve > 0; )
            c["xn" + ve] = 0,
            c["xs" + ve] = "";
        c.xs0 = "",
        c._next = c._prev = c.xfirst = c.data = c.plugin = c.setRatio = c.rxp = null,
        c.appendXtra = function(t, e, i, n, a, r) {
            var o = this
              , s = o.l;
            return o["xs" + s] += r && s ? " " + t : t || "",
            i || 0 === s || o.plugin ? (o.l++,
            o.type = o.setRatio ? 2 : 1,
            o["xs" + o.l] = n || "",
            s > 0 ? (o.data["xn" + s] = e + i,
            o.rxp["xn" + s] = a,
            o["xn" + s] = e,
            o.plugin || (o.xfirst = new fe(o,"xn" + s,e,i,o.xfirst || o,0,o.n,a,o.pr),
            o.xfirst.xs0 = 0),
            o) : (o.data = {
                s: e + i
            },
            o.rxp = {},
            o.s = e,
            o.c = i,
            o.r = a,
            o)) : (o["xs" + s] += e + (n || ""),
            o)
        }
        ;
        var _e = function(t, e) {
            e = e || {},
            this.p = e.prefix ? X(t) || t : t,
            l[t] = l[this.p] = this,
            this.format = e.formatter || de(e.defaultValue, e.color, e.collapsible, e.multi),
            e.parser && (this.parse = e.parser),
            this.clrs = e.color,
            this.multi = e.multi,
            this.keyword = e.keyword,
            this.dflt = e.defaultValue,
            this.pr = e.priority || 0
        }
          , ye = F._registerComplexSpecialProp = function(t, e, i) {
            "object" != typeof e && (e = {
                parser: i
            });
            var n, a, r = t.split(","), o = e.defaultValue;
            for (i = i || [o],
            n = 0; r.length > n; n++)
                e.prefix = 0 === n && e.prefix,
                e.defaultValue = i[n] || o,
                a = new _e(r[n],e)
        }
          , be = function(t) {
            if (!l[t]) {
                var e = t.charAt(0).toUpperCase() + t.substr(1) + "Plugin";
                ye(t, {
                    parser: function(t, i, n, a, r, o, c) {
                        var u = s.com.greensock.plugins[e];
                        return u ? (u._cssRegister(),
                        l[n].parse(t, i, n, a, r, o, c)) : (W("Error: " + e + " js file not loaded."),
                        r)
                    }
                })
            }
        };
        c = _e.prototype,
        c.parseComplex = function(t, e, i, n, a, r) {
            var o, s, l, c, u, d, h = this.keyword;
            if (this.multi && (P.test(i) || P.test(e) ? (s = e.replace(P, "|").split("|"),
            l = i.replace(P, "|").split("|")) : h && (s = [e],
            l = [i])),
            l) {
                for (c = l.length > s.length ? l.length : s.length,
                o = 0; c > o; o++)
                    e = s[o] = s[o] || this.dflt,
                    i = l[o] = l[o] || this.dflt,
                    h && (u = e.indexOf(h),
                    d = i.indexOf(h),
                    u !== d && (-1 === d ? s[o] = s[o].split(h).join("") : -1 === u && (s[o] += " " + h)));
                e = s.join(", "),
                i = l.join(", ")
            }
            return ge(t, this.p, e, i, this.clrs, this.dflt, n, this.pr, a, r)
        }
        ,
        c.parse = function(t, e, i, n, r, o) {
            return this.parseComplex(t.style, this.format(G(t, this.p, a, !1, this.dflt)), this.format(e), r, o)
        }
        ,
        o.registerSpecialProp = function(t, e, i) {
            ye(t, {
                parser: function(t, n, a, r, o, s) {
                    var l = new fe(t,a,0,0,o,2,a,!1,i);
                    return l.plugin = s,
                    l.setRatio = e(t, n, r._tween, a),
                    l
                },
                priority: i
            })
        }
        ,
        o.useSVGTransformAttr = h || p;
        var xe, we = "scaleX,scaleY,scaleZ,x,y,z,skewX,skewY,rotation,rotationX,rotationY,perspective,xPercent,yPercent".split(","), Te = X("transform"), Ce = q + "transform", Se = X("transformOrigin"), ke = null !== X("perspective"), $e = F.Transform = function() {
            this.perspective = parseFloat(o.defaultTransformPerspective) || 0,
            this.force3D = o.defaultForce3D !== !1 && ke ? o.defaultForce3D || "auto" : !1
        }
        , De = window.SVGElement, Ae = function(t, e, i) {
            var n, a = I.createElementNS("http://www.w3.org/2000/svg", t), r = /([a-z])([A-Z])/g;
            for (n in i)
                a.setAttributeNS(null, n.replace(r, "$1-$2").toLowerCase(), i[n]);
            return e.appendChild(a),
            a
        }, Oe = I.documentElement, Ee = function() {
            var t, e, i, n = m || /Android/i.test(U) && !window.chrome;
            return I.createElementNS && !n && (t = Ae("svg", Oe),
            e = Ae("rect", t, {
                width: 100,
                height: 50,
                x: 100
            }),
            i = e.getBoundingClientRect().width,
            e.style[Se] = "50% 50%",
            e.style[Te] = "scaleX(0.5)",
            n = i === e.getBoundingClientRect().width && !(p && ke),
            Oe.removeChild(t)),
            n
        }(), Pe = function(t, e, i, n, a) {
            var r, s, l, c, u, d, h, p, f, m, g, v, _, y, b = t._gsTransform, x = Re(t, !0);
            b && (_ = b.xOrigin,
            y = b.yOrigin),
            (!n || 2 > (r = n.split(" ")).length) && (h = t.getBBox(),
            e = ne(e).split(" "),
            r = [(-1 !== e[0].indexOf("%") ? parseFloat(e[0]) / 100 * h.width : parseFloat(e[0])) + h.x, (-1 !== e[1].indexOf("%") ? parseFloat(e[1]) / 100 * h.height : parseFloat(e[1])) + h.y]),
            i.xOrigin = c = parseFloat(r[0]),
            i.yOrigin = u = parseFloat(r[1]),
            n && x !== Me && (d = x[0],
            h = x[1],
            p = x[2],
            f = x[3],
            m = x[4],
            g = x[5],
            v = d * f - h * p,
            s = c * (f / v) + u * (-p / v) + (p * g - f * m) / v,
            l = c * (-h / v) + u * (d / v) - (d * g - h * m) / v,
            c = i.xOrigin = r[0] = s,
            u = i.yOrigin = r[1] = l),
            b && (a || a !== !1 && o.defaultSmoothOrigin !== !1 ? (s = c - _,
            l = u - y,
            b.xOffset += s * x[0] + l * x[2] - s,
            b.yOffset += s * x[1] + l * x[3] - l) : b.xOffset = b.yOffset = 0),
            t.setAttribute("data-svg-origin", r.join(" "))
        }, Ne = function(t) {
            return !!(De && "function" == typeof t.getBBox && t.getCTM && (!t.parentNode || t.parentNode.getBBox && t.parentNode.getCTM))
        }, Me = [1, 0, 0, 1, 0, 0], Re = function(t, e) {
            var i, n, a, r, o, s = t._gsTransform || new $e, l = 1e5;
            if (Te ? n = G(t, Ce, null, !0) : t.currentStyle && (n = t.currentStyle.filter.match(O),
            n = n && 4 === n.length ? [n[0].substr(4), Number(n[2].substr(4)), Number(n[1].substr(4)), n[3].substr(4), s.x || 0, s.y || 0].join(",") : ""),
            i = !n || "none" === n || "matrix(1, 0, 0, 1, 0, 0)" === n,
            (s.svg || t.getBBox && Ne(t)) && (i && -1 !== (t.style[Te] + "").indexOf("matrix") && (n = t.style[Te],
            i = 0),
            a = t.getAttribute("transform"),
            i && a && (-1 !== a.indexOf("matrix") ? (n = a,
            i = 0) : -1 !== a.indexOf("translate") && (n = "matrix(1,0,0,1," + a.match(/(?:\-|\b)[\d\-\.e]+\b/gi).join(",") + ")",
            i = 0))),
            i)
                return Me;
            for (a = (n || "").match(/(?:\-|\b)[\d\-\.e]+\b/gi) || [],
            ve = a.length; --ve > -1; )
                r = Number(a[ve]),
                a[ve] = (o = r - (r |= 0)) ? (0 | o * l + (0 > o ? -.5 : .5)) / l + r : r;
            return e && a.length > 6 ? [a[0], a[1], a[4], a[5], a[12], a[13]] : a
        }, Ie = F.getTransform = function(t, i, n, r) {
            if (t._gsTransform && n && !r)
                return t._gsTransform;
            var s, l, c, u, d, h, p = n ? t._gsTransform || new $e : new $e, f = 0 > p.scaleX, m = 2e-5, g = 1e5, v = ke ? parseFloat(G(t, Se, i, !1, "0 0 0").split(" ")[2]) || p.zOrigin || 0 : 0, _ = parseFloat(o.defaultTransformPerspective) || 0;
            if (p.svg = !(!t.getBBox || !Ne(t)),
            p.svg && (Pe(t, G(t, Se, a, !1, "50% 50%") + "", p, t.getAttribute("data-svg-origin")),
            xe = o.useSVGTransformAttr || Ee),
            s = Re(t),
            s !== Me) {
                if (16 === s.length) {
                    var y, b, x, w, T, C = s[0], S = s[1], k = s[2], $ = s[3], D = s[4], A = s[5], O = s[6], E = s[7], P = s[8], N = s[9], R = s[10], I = s[12], B = s[13], L = s[14], j = s[11], F = Math.atan2(O, R);
                    p.zOrigin && (L = -p.zOrigin,
                    I = P * L - s[12],
                    B = N * L - s[13],
                    L = R * L + p.zOrigin - s[14]),
                    p.rotationX = F * M,
                    F && (w = Math.cos(-F),
                    T = Math.sin(-F),
                    y = D * w + P * T,
                    b = A * w + N * T,
                    x = O * w + R * T,
                    P = D * -T + P * w,
                    N = A * -T + N * w,
                    R = O * -T + R * w,
                    j = E * -T + j * w,
                    D = y,
                    A = b,
                    O = x),
                    F = Math.atan2(P, R),
                    p.rotationY = F * M,
                    F && (w = Math.cos(-F),
                    T = Math.sin(-F),
                    y = C * w - P * T,
                    b = S * w - N * T,
                    x = k * w - R * T,
                    N = S * T + N * w,
                    R = k * T + R * w,
                    j = $ * T + j * w,
                    C = y,
                    S = b,
                    k = x),
                    F = Math.atan2(S, C),
                    p.rotation = F * M,
                    F && (w = Math.cos(-F),
                    T = Math.sin(-F),
                    C = C * w + D * T,
                    b = S * w + A * T,
                    A = S * -T + A * w,
                    O = k * -T + O * w,
                    S = b),
                    p.rotationX && Math.abs(p.rotationX) + Math.abs(p.rotation) > 359.9 && (p.rotationX = p.rotation = 0,
                    p.rotationY += 180),
                    p.scaleX = (0 | Math.sqrt(C * C + S * S) * g + .5) / g,
                    p.scaleY = (0 | Math.sqrt(A * A + N * N) * g + .5) / g,
                    p.scaleZ = (0 | Math.sqrt(O * O + R * R) * g + .5) / g,
                    p.skewX = 0,
                    p.perspective = j ? 1 / (0 > j ? -j : j) : 0,
                    p.x = I,
                    p.y = B,
                    p.z = L,
                    p.svg && (p.x -= p.xOrigin - (p.xOrigin * C - p.yOrigin * D),
                    p.y -= p.yOrigin - (p.yOrigin * S - p.xOrigin * A))
                } else if (!(ke && !r && s.length && p.x === s[4] && p.y === s[5] && (p.rotationX || p.rotationY) || void 0 !== p.x && "none" === G(t, "display", i))) {
                    var U = s.length >= 6
                      , z = U ? s[0] : 1
                      , H = s[1] || 0
                      , W = s[2] || 0
                      , q = U ? s[3] : 1;
                    p.x = s[4] || 0,
                    p.y = s[5] || 0,
                    c = Math.sqrt(z * z + H * H),
                    u = Math.sqrt(q * q + W * W),
                    d = z || H ? Math.atan2(H, z) * M : p.rotation || 0,
                    h = W || q ? Math.atan2(W, q) * M + d : p.skewX || 0,
                    Math.abs(h) > 90 && 270 > Math.abs(h) && (f ? (c *= -1,
                    h += 0 >= d ? 180 : -180,
                    d += 0 >= d ? 180 : -180) : (u *= -1,
                    h += 0 >= h ? 180 : -180)),
                    p.scaleX = c,
                    p.scaleY = u,
                    p.rotation = d,
                    p.skewX = h,
                    ke && (p.rotationX = p.rotationY = p.z = 0,
                    p.perspective = _,
                    p.scaleZ = 1),
                    p.svg && (p.x -= p.xOrigin - (p.xOrigin * z + p.yOrigin * W),
                    p.y -= p.yOrigin - (p.xOrigin * H + p.yOrigin * q))
                }
                p.zOrigin = v;
                for (l in p)
                    m > p[l] && p[l] > -m && (p[l] = 0)
            }
            return n && (t._gsTransform = p,
            p.svg && (xe && t.style[Te] ? e.delayedCall(.001, function() {
                Fe(t.style, Te)
            }) : !xe && t.getAttribute("transform") && e.delayedCall(.001, function() {
                t.removeAttribute("transform")
            }))),
            p
        }
        , Be = function(t) {
            var e, i, n = this.data, a = -n.rotation * N, r = a + n.skewX * N, o = 1e5, s = (0 | Math.cos(a) * n.scaleX * o) / o, l = (0 | Math.sin(a) * n.scaleX * o) / o, c = (0 | Math.sin(r) * -n.scaleY * o) / o, u = (0 | Math.cos(r) * n.scaleY * o) / o, d = this.t.style, h = this.t.currentStyle;
            if (h) {
                i = l,
                l = -c,
                c = -i,
                e = h.filter,
                d.filter = "";
                var p, f, g = this.t.offsetWidth, v = this.t.offsetHeight, _ = "absolute" !== h.position, y = "progid:DXImageTransform.Microsoft.Matrix(M11=" + s + ", M12=" + l + ", M21=" + c + ", M22=" + u, w = n.x + g * n.xPercent / 100, T = n.y + v * n.yPercent / 100;
                if (null != n.ox && (p = (n.oxp ? .01 * g * n.ox : n.ox) - g / 2,
                f = (n.oyp ? .01 * v * n.oy : n.oy) - v / 2,
                w += p - (p * s + f * l),
                T += f - (p * c + f * u)),
                _ ? (p = g / 2,
                f = v / 2,
                y += ", Dx=" + (p - (p * s + f * l) + w) + ", Dy=" + (f - (p * c + f * u) + T) + ")") : y += ", sizingMethod='auto expand')",
                d.filter = -1 !== e.indexOf("DXImageTransform.Microsoft.Matrix(") ? e.replace(E, y) : y + " " + e,
                (0 === t || 1 === t) && 1 === s && 0 === l && 0 === c && 1 === u && (_ && -1 === y.indexOf("Dx=0, Dy=0") || x.test(e) && 100 !== parseFloat(RegExp.$1) || -1 === e.indexOf("gradient(" && e.indexOf("Alpha")) && d.removeAttribute("filter")),
                !_) {
                    var C, S, k, $ = 8 > m ? 1 : -1;
                    for (p = n.ieOffsetX || 0,
                    f = n.ieOffsetY || 0,
                    n.ieOffsetX = Math.round((g - ((0 > s ? -s : s) * g + (0 > l ? -l : l) * v)) / 2 + w),
                    n.ieOffsetY = Math.round((v - ((0 > u ? -u : u) * v + (0 > c ? -c : c) * g)) / 2 + T),
                    ve = 0; 4 > ve; ve++)
                        S = ee[ve],
                        C = h[S],
                        i = -1 !== C.indexOf("px") ? parseFloat(C) : Q(this.t, S, parseFloat(C), C.replace(b, "")) || 0,
                        k = i !== n[S] ? 2 > ve ? -n.ieOffsetX : -n.ieOffsetY : 2 > ve ? p - n.ieOffsetX : f - n.ieOffsetY,
                        d[S] = (n[S] = Math.round(i - k * (0 === ve || 2 === ve ? 1 : $))) + "px"
                }
            }
        }, Le = F.set3DTransformRatio = F.setTransformRatio = function(t) {
            var e, i, n, a, r, o, s, l, c, u, d, h, f, m, g, v, _, y, b, x, w, T, C, S = this.data, k = this.t.style, $ = S.rotation, D = S.rotationX, A = S.rotationY, O = S.scaleX, E = S.scaleY, P = S.scaleZ, M = S.x, R = S.y, I = S.z, B = S.svg, L = S.perspective, j = S.force3D;
            if (!((1 !== t && 0 !== t || "auto" !== j || this.tween._totalTime !== this.tween._totalDuration && this.tween._totalTime) && j || I || L || A || D) || xe && B || !ke)
                return void ($ || S.skewX || B ? ($ *= N,
                T = S.skewX * N,
                C = 1e5,
                e = Math.cos($) * O,
                a = Math.sin($) * O,
                i = Math.sin($ - T) * -E,
                r = Math.cos($ - T) * E,
                T && "simple" === S.skewType && (_ = Math.tan(T),
                _ = Math.sqrt(1 + _ * _),
                i *= _,
                r *= _,
                S.skewY && (e *= _,
                a *= _)),
                B && (M += S.xOrigin - (S.xOrigin * e + S.yOrigin * i) + S.xOffset,
                R += S.yOrigin - (S.xOrigin * a + S.yOrigin * r) + S.yOffset,
                xe && (S.xPercent || S.yPercent) && (m = this.t.getBBox(),
                M += .01 * S.xPercent * m.width,
                R += .01 * S.yPercent * m.height),
                m = 1e-6,
                m > M && M > -m && (M = 0),
                m > R && R > -m && (R = 0)),
                b = (0 | e * C) / C + "," + (0 | a * C) / C + "," + (0 | i * C) / C + "," + (0 | r * C) / C + "," + M + "," + R + ")",
                B && xe ? this.t.setAttribute("transform", "matrix(" + b) : k[Te] = (S.xPercent || S.yPercent ? "translate(" + S.xPercent + "%," + S.yPercent + "%) matrix(" : "matrix(") + b) : k[Te] = (S.xPercent || S.yPercent ? "translate(" + S.xPercent + "%," + S.yPercent + "%) matrix(" : "matrix(") + O + ",0,0," + E + "," + M + "," + R + ")");
            if (p && (m = 1e-4,
            m > O && O > -m && (O = P = 2e-5),
            m > E && E > -m && (E = P = 2e-5),
            !L || S.z || S.rotationX || S.rotationY || (L = 0)),
            $ || S.skewX)
                $ *= N,
                g = e = Math.cos($),
                v = a = Math.sin($),
                S.skewX && ($ -= S.skewX * N,
                g = Math.cos($),
                v = Math.sin($),
                "simple" === S.skewType && (_ = Math.tan(S.skewX * N),
                _ = Math.sqrt(1 + _ * _),
                g *= _,
                v *= _,
                S.skewY && (e *= _,
                a *= _))),
                i = -v,
                r = g;
            else {
                if (!(A || D || 1 !== P || L || B))
                    return void (k[Te] = (S.xPercent || S.yPercent ? "translate(" + S.xPercent + "%," + S.yPercent + "%) translate3d(" : "translate3d(") + M + "px," + R + "px," + I + "px)" + (1 !== O || 1 !== E ? " scale(" + O + "," + E + ")" : ""));
                e = r = 1,
                i = a = 0
            }
            c = 1,
            n = o = s = l = u = d = 0,
            h = L ? -1 / L : 0,
            f = S.zOrigin,
            m = 1e-6,
            x = ",",
            w = "0",
            $ = A * N,
            $ && (g = Math.cos($),
            v = Math.sin($),
            s = -v,
            u = h * -v,
            n = e * v,
            o = a * v,
            c = g,
            h *= g,
            e *= g,
            a *= g),
            $ = D * N,
            $ && (g = Math.cos($),
            v = Math.sin($),
            _ = i * g + n * v,
            y = r * g + o * v,
            l = c * v,
            d = h * v,
            n = i * -v + n * g,
            o = r * -v + o * g,
            c *= g,
            h *= g,
            i = _,
            r = y),
            1 !== P && (n *= P,
            o *= P,
            c *= P,
            h *= P),
            1 !== E && (i *= E,
            r *= E,
            l *= E,
            d *= E),
            1 !== O && (e *= O,
            a *= O,
            s *= O,
            u *= O),
            (f || B) && (f && (M += n * -f,
            R += o * -f,
            I += c * -f + f),
            B && (M += S.xOrigin - (S.xOrigin * e + S.yOrigin * i) + S.xOffset,
            R += S.yOrigin - (S.xOrigin * a + S.yOrigin * r) + S.yOffset),
            m > M && M > -m && (M = w),
            m > R && R > -m && (R = w),
            m > I && I > -m && (I = 0)),
            b = S.xPercent || S.yPercent ? "translate(" + S.xPercent + "%," + S.yPercent + "%) matrix3d(" : "matrix3d(",
            b += (m > e && e > -m ? w : e) + x + (m > a && a > -m ? w : a) + x + (m > s && s > -m ? w : s),
            b += x + (m > u && u > -m ? w : u) + x + (m > i && i > -m ? w : i) + x + (m > r && r > -m ? w : r),
            D || A ? (b += x + (m > l && l > -m ? w : l) + x + (m > d && d > -m ? w : d) + x + (m > n && n > -m ? w : n),
            b += x + (m > o && o > -m ? w : o) + x + (m > c && c > -m ? w : c) + x + (m > h && h > -m ? w : h) + x) : b += ",0,0,0,0,1,0,",
            b += M + x + R + x + I + x + (L ? 1 + -I / L : 1) + ")",
            k[Te] = b
        }
        ;
        c = $e.prototype,
        c.x = c.y = c.z = c.skewX = c.skewY = c.rotation = c.rotationX = c.rotationY = c.zOrigin = c.xPercent = c.yPercent = c.xOffset = c.yOffset = 0,
        c.scaleX = c.scaleY = c.scaleZ = 1,
        ye("transform,scale,scaleX,scaleY,scaleZ,x,y,z,rotation,rotationX,rotationY,rotationZ,skewX,skewY,shortRotation,shortRotationX,shortRotationY,shortRotationZ,transformOrigin,svgOrigin,transformPerspective,directionalRotation,parseTransform,force3D,skewType,xPercent,yPercent,smoothOrigin", {
            parser: function(t, e, i, n, r, s, l) {
                if (n._lastParsedTransform === l)
                    return r;
                n._lastParsedTransform = l;
                var c, u, d, h, p, f, m, g, v, _ = t._gsTransform, y = n._transform = Ie(t, a, !0, l.parseTransform), b = t.style, x = 1e-6, w = we.length, T = l, C = {}, S = "transformOrigin";
                if ("string" == typeof T.transform && Te)
                    d = L.style,
                    d[Te] = T.transform,
                    d.display = "block",
                    d.position = "absolute",
                    I.body.appendChild(L),
                    c = Ie(L, null, !1),
                    I.body.removeChild(L),
                    null != T.xPercent && (c.xPercent = re(T.xPercent, y.xPercent)),
                    null != T.yPercent && (c.yPercent = re(T.yPercent, y.yPercent));
                else if ("object" == typeof T) {
                    if (c = {
                        scaleX: re(null != T.scaleX ? T.scaleX : T.scale, y.scaleX),
                        scaleY: re(null != T.scaleY ? T.scaleY : T.scale, y.scaleY),
                        scaleZ: re(T.scaleZ, y.scaleZ),
                        x: re(T.x, y.x),
                        y: re(T.y, y.y),
                        z: re(T.z, y.z),
                        xPercent: re(T.xPercent, y.xPercent),
                        yPercent: re(T.yPercent, y.yPercent),
                        perspective: re(T.transformPerspective, y.perspective)
                    },
                    m = T.directionalRotation,
                    null != m)
                        if ("object" == typeof m)
                            for (d in m)
                                T[d] = m[d];
                        else
                            T.rotation = m;
                    "string" == typeof T.x && -1 !== T.x.indexOf("%") && (c.x = 0,
                    c.xPercent = re(T.x, y.xPercent)),
                    "string" == typeof T.y && -1 !== T.y.indexOf("%") && (c.y = 0,
                    c.yPercent = re(T.y, y.yPercent)),
                    c.rotation = oe("rotation"in T ? T.rotation : "shortRotation"in T ? T.shortRotation + "_short" : "rotationZ"in T ? T.rotationZ : y.rotation, y.rotation, "rotation", C),
                    ke && (c.rotationX = oe("rotationX"in T ? T.rotationX : "shortRotationX"in T ? T.shortRotationX + "_short" : y.rotationX || 0, y.rotationX, "rotationX", C),
                    c.rotationY = oe("rotationY"in T ? T.rotationY : "shortRotationY"in T ? T.shortRotationY + "_short" : y.rotationY || 0, y.rotationY, "rotationY", C)),
                    c.skewX = null == T.skewX ? y.skewX : oe(T.skewX, y.skewX),
                    c.skewY = null == T.skewY ? y.skewY : oe(T.skewY, y.skewY),
                    (u = c.skewY - y.skewY) && (c.skewX += u,
                    c.rotation += u)
                }
                for (ke && null != T.force3D && (y.force3D = T.force3D,
                f = !0),
                y.skewType = T.skewType || y.skewType || o.defaultSkewType,
                p = y.force3D || y.z || y.rotationX || y.rotationY || c.z || c.rotationX || c.rotationY || c.perspective,
                p || null == T.scale || (c.scaleZ = 1); --w > -1; )
                    i = we[w],
                    h = c[i] - y[i],
                    (h > x || -x > h || null != T[i] || null != R[i]) && (f = !0,
                    r = new fe(y,i,y[i],h,r),
                    i in C && (r.e = C[i]),
                    r.xs0 = 0,
                    r.plugin = s,
                    n._overwriteProps.push(r.n));
                return h = T.transformOrigin,
                y.svg && (h || T.svgOrigin) && (g = y.xOffset,
                v = y.yOffset,
                Pe(t, ne(h), c, T.svgOrigin, T.smoothOrigin),
                r = me(y, "xOrigin", (_ ? y : c).xOrigin, c.xOrigin, r, S),
                r = me(y, "yOrigin", (_ ? y : c).yOrigin, c.yOrigin, r, S),
                (g !== y.xOffset || v !== y.yOffset) && (r = me(y, "xOffset", _ ? g : y.xOffset, y.xOffset, r, S),
                r = me(y, "yOffset", _ ? v : y.yOffset, y.yOffset, r, S)),
                h = xe ? null : "0px 0px"),
                (h || ke && p && y.zOrigin) && (Te ? (f = !0,
                i = Se,
                h = (h || G(t, i, a, !1, "50% 50%")) + "",
                r = new fe(b,i,0,0,r,-1,S),
                r.b = b[i],
                r.plugin = s,
                ke ? (d = y.zOrigin,
                h = h.split(" "),
                y.zOrigin = (h.length > 2 && (0 === d || "0px" !== h[2]) ? parseFloat(h[2]) : d) || 0,
                r.xs0 = r.e = h[0] + " " + (h[1] || "50%") + " 0px",
                r = new fe(y,"zOrigin",0,0,r,-1,r.n),
                r.b = d,
                r.xs0 = r.e = y.zOrigin) : r.xs0 = r.e = h) : ne(h + "", y)),
                f && (n._transformType = y.svg && xe || !p && 3 !== this._transformType ? 2 : 3),
                r
            },
            prefix: !0
        }),
        ye("boxShadow", {
            defaultValue: "0px 0px 0px 0px #999",
            prefix: !0,
            color: !0,
            multi: !0,
            keyword: "inset"
        }),
        ye("borderRadius", {
            defaultValue: "0px",
            parser: function(t, e, i, r, o) {
                e = this.format(e);
                var s, l, c, u, d, h, p, f, m, g, v, _, y, b, x, w, T = ["borderTopLeftRadius", "borderTopRightRadius", "borderBottomRightRadius", "borderBottomLeftRadius"], C = t.style;
                for (m = parseFloat(t.offsetWidth),
                g = parseFloat(t.offsetHeight),
                s = e.split(" "),
                l = 0; T.length > l; l++)
                    this.p.indexOf("border") && (T[l] = X(T[l])),
                    d = u = G(t, T[l], a, !1, "0px"),
                    -1 !== d.indexOf(" ") && (u = d.split(" "),
                    d = u[0],
                    u = u[1]),
                    h = c = s[l],
                    p = parseFloat(d),
                    _ = d.substr((p + "").length),
                    y = "=" === h.charAt(1),
                    y ? (f = parseInt(h.charAt(0) + "1", 10),
                    h = h.substr(2),
                    f *= parseFloat(h),
                    v = h.substr((f + "").length - (0 > f ? 1 : 0)) || "") : (f = parseFloat(h),
                    v = h.substr((f + "").length)),
                    "" === v && (v = n[i] || _),
                    v !== _ && (b = Q(t, "borderLeft", p, _),
                    x = Q(t, "borderTop", p, _),
                    "%" === v ? (d = 100 * (b / m) + "%",
                    u = 100 * (x / g) + "%") : "em" === v ? (w = Q(t, "borderLeft", 1, "em"),
                    d = b / w + "em",
                    u = x / w + "em") : (d = b + "px",
                    u = x + "px"),
                    y && (h = parseFloat(d) + f + v,
                    c = parseFloat(u) + f + v)),
                    o = ge(C, T[l], d + " " + u, h + " " + c, !1, "0px", o);
                return o
            },
            prefix: !0,
            formatter: de("0px 0px 0px 0px", !1, !0)
        }),
        ye("backgroundPosition", {
            defaultValue: "0 0",
            parser: function(t, e, i, n, r, o) {
                var s, l, c, u, d, h, p = "background-position", f = a || V(t, null), g = this.format((f ? m ? f.getPropertyValue(p + "-x") + " " + f.getPropertyValue(p + "-y") : f.getPropertyValue(p) : t.currentStyle.backgroundPositionX + " " + t.currentStyle.backgroundPositionY) || "0 0"), v = this.format(e);
                if (-1 !== g.indexOf("%") != (-1 !== v.indexOf("%")) && (h = G(t, "backgroundImage").replace($, ""),
                h && "none" !== h)) {
                    for (s = g.split(" "),
                    l = v.split(" "),
                    j.setAttribute("src", h),
                    c = 2; --c > -1; )
                        g = s[c],
                        u = -1 !== g.indexOf("%"),
                        u !== (-1 !== l[c].indexOf("%")) && (d = 0 === c ? t.offsetWidth - j.width : t.offsetHeight - j.height,
                        s[c] = u ? parseFloat(g) / 100 * d + "px" : 100 * (parseFloat(g) / d) + "%");
                    g = s.join(" ")
                }
                return this.parseComplex(t.style, g, v, r, o)
            },
            formatter: ne
        }),
        ye("backgroundSize", {
            defaultValue: "0 0",
            formatter: ne
        }),
        ye("perspective", {
            defaultValue: "0px",
            prefix: !0
        }),
        ye("perspectiveOrigin", {
            defaultValue: "50% 50%",
            prefix: !0
        }),
        ye("transformStyle", {
            prefix: !0
        }),
        ye("backfaceVisibility", {
            prefix: !0
        }),
        ye("userSelect", {
            prefix: !0
        }),
        ye("margin", {
            parser: he("marginTop,marginRight,marginBottom,marginLeft")
        }),
        ye("padding", {
            parser: he("paddingTop,paddingRight,paddingBottom,paddingLeft")
        }),
        ye("clip", {
            defaultValue: "rect(0px,0px,0px,0px)",
            parser: function(t, e, i, n, r, o) {
                var s, l, c;
                return 9 > m ? (l = t.currentStyle,
                c = 8 > m ? " " : ",",
                s = "rect(" + l.clipTop + c + l.clipRight + c + l.clipBottom + c + l.clipLeft + ")",
                e = this.format(e).split(",").join(c)) : (s = this.format(G(t, this.p, a, !1, this.dflt)),
                e = this.format(e)),
                this.parseComplex(t.style, s, e, r, o)
            }
        }),
        ye("textShadow", {
            defaultValue: "0px 0px 0px #999",
            color: !0,
            multi: !0
        }),
        ye("autoRound,strictUnits", {
            parser: function(t, e, i, n, a) {
                return a
            }
        }),
        ye("border", {
            defaultValue: "0px solid #000",
            parser: function(t, e, i, n, r, o) {
                return this.parseComplex(t.style, this.format(G(t, "borderTopWidth", a, !1, "0px") + " " + G(t, "borderTopStyle", a, !1, "solid") + " " + G(t, "borderTopColor", a, !1, "#000")), this.format(e), r, o)
            },
            color: !0,
            formatter: function(t) {
                var e = t.split(" ");
                return e[0] + " " + (e[1] || "solid") + " " + (t.match(ue) || ["#000"])[0]
            }
        }),
        ye("borderWidth", {
            parser: he("borderTopWidth,borderRightWidth,borderBottomWidth,borderLeftWidth")
        }),
        ye("float,cssFloat,styleFloat", {
            parser: function(t, e, i, n, a) {
                var r = t.style
                  , o = "cssFloat"in r ? "cssFloat" : "styleFloat";
                return new fe(r,o,0,0,a,-1,i,!1,0,r[o],e)
            }
        });
        var je = function(t) {
            var e, i = this.t, n = i.filter || G(this.data, "filter") || "", a = 0 | this.s + this.c * t;
            100 === a && (-1 === n.indexOf("atrix(") && -1 === n.indexOf("radient(") && -1 === n.indexOf("oader(") ? (i.removeAttribute("filter"),
            e = !G(this.data, "filter")) : (i.filter = n.replace(T, ""),
            e = !0)),
            e || (this.xn1 && (i.filter = n = n || "alpha(opacity=" + a + ")"),
            -1 === n.indexOf("pacity") ? 0 === a && this.xn1 || (i.filter = n + " alpha(opacity=" + a + ")") : i.filter = n.replace(x, "opacity=" + a))
        };
        ye("opacity,alpha,autoAlpha", {
            defaultValue: "1",
            parser: function(t, e, i, n, r, o) {
                var s = parseFloat(G(t, "opacity", a, !1, "1"))
                  , l = t.style
                  , c = "autoAlpha" === i;
                return "string" == typeof e && "=" === e.charAt(1) && (e = ("-" === e.charAt(0) ? -1 : 1) * parseFloat(e.substr(2)) + s),
                c && 1 === s && "hidden" === G(t, "visibility", a) && 0 !== e && (s = 0),
                z ? r = new fe(l,"opacity",s,e - s,r) : (r = new fe(l,"opacity",100 * s,100 * (e - s),r),
                r.xn1 = c ? 1 : 0,
                l.zoom = 1,
                r.type = 2,
                r.b = "alpha(opacity=" + r.s + ")",
                r.e = "alpha(opacity=" + (r.s + r.c) + ")",
                r.data = t,
                r.plugin = o,
                r.setRatio = je),
                c && (r = new fe(l,"visibility",0,0,r,-1,null,!1,0,0 !== s ? "inherit" : "hidden",0 === e ? "hidden" : "inherit"),
                r.xs0 = "inherit",
                n._overwriteProps.push(r.n),
                n._overwriteProps.push(i)),
                r
            }
        });
        var Fe = function(t, e) {
            e && (t.removeProperty ? (("ms" === e.substr(0, 2) || "webkit" === e.substr(0, 6)) && (e = "-" + e),
            t.removeProperty(e.replace(S, "-$1").toLowerCase())) : t.removeAttribute(e))
        }
          , Ue = function(t) {
            if (this.t._gsClassPT = this,
            1 === t || 0 === t) {
                this.t.setAttribute("class", 0 === t ? this.b : this.e);
                for (var e = this.data, i = this.t.style; e; )
                    e.v ? i[e.p] = e.v : Fe(i, e.p),
                    e = e._next;
                1 === t && this.t._gsClassPT === this && (this.t._gsClassPT = null)
            } else
                this.t.getAttribute("class") !== this.e && this.t.setAttribute("class", this.e)
        };
        ye("className", {
            parser: function(t, e, n, r, o, s, l) {
                var c, u, d, h, p, f = t.getAttribute("class") || "", m = t.style.cssText;
                if (o = r._classNamePT = new fe(t,n,0,0,o,2),
                o.setRatio = Ue,
                o.pr = -11,
                i = !0,
                o.b = f,
                u = K(t, a),
                d = t._gsClassPT) {
                    for (h = {},
                    p = d.data; p; )
                        h[p.p] = 1,
                        p = p._next;
                    d.setRatio(1)
                }
                return t._gsClassPT = o,
                o.e = "=" !== e.charAt(1) ? e : f.replace(RegExp("\\s*\\b" + e.substr(2) + "\\b"), "") + ("+" === e.charAt(0) ? " " + e.substr(2) : ""),
                t.setAttribute("class", o.e),
                c = Z(t, u, K(t), l, h),
                t.setAttribute("class", f),
                o.data = c.firstMPT,
                t.style.cssText = m,
                o = o.xfirst = r.parse(t, c.difs, o, s)
            }
        });
        var ze = function(t) {
            if ((1 === t || 0 === t) && this.data._totalTime === this.data._totalDuration && "isFromStart" !== this.data.data) {
                var e, i, n, a, r, o = this.t.style, s = l.transform.parse;
                if ("all" === this.e)
                    o.cssText = "",
                    a = !0;
                else
                    for (e = this.e.split(" ").join("").split(","),
                    n = e.length; --n > -1; )
                        i = e[n],
                        l[i] && (l[i].parse === s ? a = !0 : i = "transformOrigin" === i ? Se : l[i].p),
                        Fe(o, i);
                a && (Fe(o, Te),
                r = this.t._gsTransform,
                r && (r.svg && this.t.removeAttribute("data-svg-origin"),
                delete this.t._gsTransform))
            }
        };
        for (ye("clearProps", {
            parser: function(t, e, n, a, r) {
                return r = new fe(t,n,0,0,r,2),
                r.setRatio = ze,
                r.e = e,
                r.pr = -10,
                r.data = a._tween,
                i = !0,
                r
            }
        }),
        c = "bezier,throwProps,physicsProps,physics2D".split(","),
        ve = c.length; ve--; )
            be(c[ve]);
        c = o.prototype,
        c._firstPT = c._lastParsedTransform = c._transform = null,
        c._onInitTween = function(t, e, s) {
            if (!t.nodeType)
                return !1;
            this._target = t,
            this._tween = s,
            this._vars = e,
            u = e.autoRound,
            i = !1,
            n = e.suffixMap || o.suffixMap,
            a = V(t, ""),
            r = this._overwriteProps;
            var c, p, m, g, v, _, y, b, x, T = t.style;
            if (d && "" === T.zIndex && (c = G(t, "zIndex", a),
            ("auto" === c || "" === c) && this._addLazySet(T, "zIndex", 0)),
            "string" == typeof e && (g = T.cssText,
            c = K(t, a),
            T.cssText = g + ";" + e,
            c = Z(t, c, K(t)).difs,
            !z && w.test(e) && (c.opacity = parseFloat(RegExp.$1)),
            e = c,
            T.cssText = g),
            this._firstPT = p = e.className ? l.className.parse(t, e.className, "className", this, null, null, e) : this.parse(t, e, null),
            this._transformType) {
                for (x = 3 === this._transformType,
                Te ? h && (d = !0,
                "" === T.zIndex && (y = G(t, "zIndex", a),
                ("auto" === y || "" === y) && this._addLazySet(T, "zIndex", 0)),
                f && this._addLazySet(T, "WebkitBackfaceVisibility", this._vars.WebkitBackfaceVisibility || (x ? "visible" : "hidden"))) : T.zoom = 1,
                m = p; m && m._next; )
                    m = m._next;
                b = new fe(t,"transform",0,0,null,2),
                this._linkCSSP(b, null, m),
                b.setRatio = Te ? Le : Be,
                b.data = this._transform || Ie(t, a, !0),
                b.tween = s,
                b.pr = -1,
                r.pop()
            }
            if (i) {
                for (; p; ) {
                    for (_ = p._next,
                    m = g; m && m.pr > p.pr; )
                        m = m._next;
                    (p._prev = m ? m._prev : v) ? p._prev._next = p : g = p,
                    (p._next = m) ? m._prev = p : v = p,
                    p = _
                }
                this._firstPT = g
            }
            return !0
        }
        ,
        c.parse = function(t, e, i, r) {
            var o, s, c, d, h, p, f, m, g, v, _ = t.style;
            for (o in e)
                p = e[o],
                s = l[o],
                s ? i = s.parse(t, p, o, this, i, r, e) : (h = G(t, o, a) + "",
                g = "string" == typeof p,
                "color" === o || "fill" === o || "stroke" === o || -1 !== o.indexOf("Color") || g && C.test(p) ? (g || (p = ce(p),
                p = (p.length > 3 ? "rgba(" : "rgb(") + p.join(",") + ")"),
                i = ge(_, o, h, p, !0, "transparent", i, 0, r)) : !g || -1 === p.indexOf(" ") && -1 === p.indexOf(",") ? (c = parseFloat(h),
                f = c || 0 === c ? h.substr((c + "").length) : "",
                ("" === h || "auto" === h) && ("width" === o || "height" === o ? (c = ie(t, o, a),
                f = "px") : "left" === o || "top" === o ? (c = J(t, o, a),
                f = "px") : (c = "opacity" !== o ? 0 : 1,
                f = "")),
                v = g && "=" === p.charAt(1),
                v ? (d = parseInt(p.charAt(0) + "1", 10),
                p = p.substr(2),
                d *= parseFloat(p),
                m = p.replace(b, "")) : (d = parseFloat(p),
                m = g ? p.replace(b, "") : ""),
                "" === m && (m = o in n ? n[o] : f),
                p = d || 0 === d ? (v ? d + c : d) + m : e[o],
                f !== m && "" !== m && (d || 0 === d) && c && (c = Q(t, o, c, f),
                "%" === m ? (c /= Q(t, o, 100, "%") / 100,
                e.strictUnits !== !0 && (h = c + "%")) : "em" === m ? c /= Q(t, o, 1, "em") : "px" !== m && (d = Q(t, o, d, m),
                m = "px"),
                v && (d || 0 === d) && (p = d + c + m)),
                v && (d += c),
                !c && 0 !== c || !d && 0 !== d ? void 0 !== _[o] && (p || "NaN" != p + "" && null != p) ? (i = new fe(_,o,d || c || 0,0,i,-1,o,!1,0,h,p),
                i.xs0 = "none" !== p || "display" !== o && -1 === o.indexOf("Style") ? p : h) : W("invalid " + o + " tween value: " + e[o]) : (i = new fe(_,o,c,d - c,i,0,o,u !== !1 && ("px" === m || "zIndex" === o),0,h,p),
                i.xs0 = m)) : i = ge(_, o, h, p, !0, null, i, 0, r)),
                r && i && !i.plugin && (i.plugin = r);
            return i
        }
        ,
        c.setRatio = function(t) {
            var e, i, n, a = this._firstPT, r = 1e-6;
            if (1 !== t || this._tween._time !== this._tween._duration && 0 !== this._tween._time)
                if (t || this._tween._time !== this._tween._duration && 0 !== this._tween._time || this._tween._rawPrevTime === -1e-6)
                    for (; a; ) {
                        if (e = a.c * t + a.s,
                        a.r ? e = Math.round(e) : r > e && e > -r && (e = 0),
                        a.type)
                            if (1 === a.type)
                                if (n = a.l,
                                2 === n)
                                    a.t[a.p] = a.xs0 + e + a.xs1 + a.xn1 + a.xs2;
                                else if (3 === n)
                                    a.t[a.p] = a.xs0 + e + a.xs1 + a.xn1 + a.xs2 + a.xn2 + a.xs3;
                                else if (4 === n)
                                    a.t[a.p] = a.xs0 + e + a.xs1 + a.xn1 + a.xs2 + a.xn2 + a.xs3 + a.xn3 + a.xs4;
                                else if (5 === n)
                                    a.t[a.p] = a.xs0 + e + a.xs1 + a.xn1 + a.xs2 + a.xn2 + a.xs3 + a.xn3 + a.xs4 + a.xn4 + a.xs5;
                                else {
                                    for (i = a.xs0 + e + a.xs1,
                                    n = 1; a.l > n; n++)
                                        i += a["xn" + n] + a["xs" + (n + 1)];
                                    a.t[a.p] = i
                                }
                            else
                                -1 === a.type ? a.t[a.p] = a.xs0 : a.setRatio && a.setRatio(t);
                        else
                            a.t[a.p] = e + a.xs0;
                        a = a._next
                    }
                else
                    for (; a; )
                        2 !== a.type ? a.t[a.p] = a.b : a.setRatio(t),
                        a = a._next;
            else
                for (; a; ) {
                    if (2 !== a.type)
                        if (a.r && -1 !== a.type)
                            if (e = Math.round(a.s + a.c),
                            a.type) {
                                if (1 === a.type) {
                                    for (n = a.l,
                                    i = a.xs0 + e + a.xs1,
                                    n = 1; a.l > n; n++)
                                        i += a["xn" + n] + a["xs" + (n + 1)];
                                    a.t[a.p] = i
                                }
                            } else
                                a.t[a.p] = e + a.xs0;
                        else
                            a.t[a.p] = a.e;
                    else
                        a.setRatio(t);
                    a = a._next
                }
        }
        ,
        c._enableTransforms = function(t) {
            this._transform = this._transform || Ie(this._target, a, !0),
            this._transformType = this._transform.svg && xe || !t && 3 !== this._transformType ? 2 : 3
        }
        ;
        var He = function() {
            this.t[this.p] = this.e,
            this.data._linkCSSP(this, this._next, null, !0)
        };
        c._addLazySet = function(t, e, i) {
            var n = this._firstPT = new fe(t,e,0,0,this._firstPT,2);
            n.e = i,
            n.setRatio = He,
            n.data = this
        }
        ,
        c._linkCSSP = function(t, e, i, n) {
            return t && (e && (e._prev = t),
            t._next && (t._next._prev = t._prev),
            t._prev ? t._prev._next = t._next : this._firstPT === t && (this._firstPT = t._next,
            n = !0),
            i ? i._next = t : n || null !== this._firstPT || (this._firstPT = t),
            t._next = e,
            t._prev = i),
            t
        }
        ,
        c._kill = function(e) {
            var i, n, a, r = e;
            if (e.autoAlpha || e.alpha) {
                r = {};
                for (n in e)
                    r[n] = e[n];
                r.opacity = 1,
                r.autoAlpha && (r.visibility = 1)
            }
            return e.className && (i = this._classNamePT) && (a = i.xfirst,
            a && a._prev ? this._linkCSSP(a._prev, i._next, a._prev._prev) : a === this._firstPT && (this._firstPT = i._next),
            i._next && this._linkCSSP(i._next, i._next._next, a._prev),
            this._classNamePT = null),
            t.prototype._kill.call(this, r)
        }
        ;
        var We = function(t, e, i) {
            var n, a, r, o;
            if (t.slice)
                for (a = t.length; --a > -1; )
                    We(t[a], e, i);
            else
                for (n = t.childNodes,
                a = n.length; --a > -1; )
                    r = n[a],
                    o = r.type,
                    r.style && (e.push(K(r)),
                    i && i.push(r)),
                    1 !== o && 9 !== o && 11 !== o || !r.childNodes.length || We(r, e, i)
        };
        return o.cascadeTo = function(t, i, n) {
            var a, r, o, s, l = e.to(t, i, n), c = [l], u = [], d = [], h = [], p = e._internals.reservedProps;
            for (t = l._targets || l.target,
            We(t, u, h),
            l.render(i, !0, !0),
            We(t, d),
            l.render(0, !0, !0),
            l._enabled(!0),
            a = h.length; --a > -1; )
                if (r = Z(h[a], u[a], d[a]),
                r.firstMPT) {
                    r = r.difs;
                    for (o in n)
                        p[o] && (r[o] = n[o]);
                    s = {};
                    for (o in r)
                        s[o] = u[a][o];
                    c.push(e.fromTo(h[a], i, s, r))
                }
            return c
        }
        ,
        t.activate([o]),
        o
    }, !0),
    function() {
        var t = _gsScope._gsDefine.plugin({
            propName: "roundProps",
            priority: -1,
            API: 2,
            init: function(t, e, i) {
                return this._tween = i,
                !0
            }
        })
          , e = t.prototype;
        e._onInitAllProps = function() {
            for (var t, e, i, n = this._tween, a = n.vars.roundProps instanceof Array ? n.vars.roundProps : n.vars.roundProps.split(","), r = a.length, o = {}, s = n._propLookup.roundProps; --r > -1; )
                o[a[r]] = 1;
            for (r = a.length; --r > -1; )
                for (t = a[r],
                e = n._firstPT; e; )
                    i = e._next,
                    e.pg ? e.t._roundProps(o, !0) : e.n === t && (this._add(e.t, t, e.s, e.c),
                    i && (i._prev = e._prev),
                    e._prev ? e._prev._next = i : n._firstPT === e && (n._firstPT = i),
                    e._next = e._prev = null,
                    n._propLookup[t] = s),
                    e = i;
            return !1
        }
        ,
        e._add = function(t, e, i, n) {
            this._addTween(t, e, i, i + n, e, !0),
            this._overwriteProps.push(e)
        }
    }(),
    function() {
        var t = /(?:\d|\-|\+|=|#|\.)*/g
          , e = /[A-Za-z%]/g;
        _gsScope._gsDefine.plugin({
            propName: "attr",
            API: 2,
            version: "0.4.0",
            init: function(i, n) {
                var a, r, o, s, l;
                if ("function" != typeof i.setAttribute)
                    return !1;
                this._target = i,
                this._proxy = {},
                this._start = {},
                this._end = {},
                this._suffix = {};
                for (a in n)
                    this._start[a] = this._proxy[a] = r = i.getAttribute(a) + "",
                    this._end[a] = o = n[a] + "",
                    this._suffix[a] = s = e.test(o) ? o.replace(t, "") : e.test(r) ? r.replace(t, "") : "",
                    s && (l = o.indexOf(s),
                    -1 !== l && (o = o.substr(0, l))),
                    this._addTween(this._proxy, a, parseFloat(r), o, a) || (this._suffix[a] = ""),
                    "=" === o.charAt(1) && (this._end[a] = this._firstPT.s + this._firstPT.c + s),
                    this._overwriteProps.push(a);
                return !0
            },
            set: function(t) {
                this._super.setRatio.call(this, t);
                for (var e, i = this._overwriteProps, n = i.length, a = 1 === t ? this._end : t ? this._proxy : this._start, r = a === this._proxy; --n > -1; )
                    e = i[n],
                    this._target.setAttribute(e, a[e] + (r ? this._suffix[e] : ""))
            }
        })
    }(),
    _gsScope._gsDefine.plugin({
        propName: "directionalRotation",
        version: "0.2.1",
        API: 2,
        init: function(t, e) {
            "object" != typeof e && (e = {
                rotation: e
            }),
            this.finals = {};
            var i, n, a, r, o, s, l = e.useRadians === !0 ? 2 * Math.PI : 360, c = 1e-6;
            for (i in e)
                "useRadians" !== i && (s = (e[i] + "").split("_"),
                n = s[0],
                a = parseFloat("function" != typeof t[i] ? t[i] : t[i.indexOf("set") || "function" != typeof t["get" + i.substr(3)] ? i : "get" + i.substr(3)]()),
                r = this.finals[i] = "string" == typeof n && "=" === n.charAt(1) ? a + parseInt(n.charAt(0) + "1", 10) * Number(n.substr(2)) : Number(n) || 0,
                o = r - a,
                s.length && (n = s.join("_"),
                -1 !== n.indexOf("short") && (o %= l,
                o !== o % (l / 2) && (o = 0 > o ? o + l : o - l)),
                -1 !== n.indexOf("_cw") && 0 > o ? o = (o + 9999999999 * l) % l - (0 | o / l) * l : -1 !== n.indexOf("ccw") && o > 0 && (o = (o - 9999999999 * l) % l - (0 | o / l) * l)),
                (o > c || -c > o) && (this._addTween(t, i, a, a + o, i),
                this._overwriteProps.push(i)));
            return !0
        },
        set: function(t) {
            var e;
            if (1 !== t)
                this._super.setRatio.call(this, t);
            else
                for (e = this._firstPT; e; )
                    e.f ? e.t[e.p](this.finals[e.p]) : e.t[e.p] = this.finals[e.p],
                    e = e._next
        }
    })._autoCSS = !0,
    _gsScope._gsDefine("easing.Back", ["easing.Ease"], function(t) {
        var e, i, n, a = _gsScope.GreenSockGlobals || _gsScope, r = a.com.greensock, o = 2 * Math.PI, s = Math.PI / 2, l = r._class, c = function(e, i) {
            var n = l("easing." + e, function() {}, !0)
              , a = n.prototype = new t;
            return a.constructor = n,
            a.getRatio = i,
            n
        }, u = t.register || function() {}
        , d = function(t, e, i, n) {
            var a = l("easing." + t, {
                easeOut: new e,
                easeIn: new i,
                easeInOut: new n
            }, !0);
            return u(a, t),
            a
        }, h = function(t, e, i) {
            this.t = t,
            this.v = e,
            i && (this.next = i,
            i.prev = this,
            this.c = i.v - e,
            this.gap = i.t - t)
        }, p = function(e, i) {
            var n = l("easing." + e, function(t) {
                this._p1 = t || 0 === t ? t : 1.70158,
                this._p2 = 1.525 * this._p1
            }, !0)
              , a = n.prototype = new t;
            return a.constructor = n,
            a.getRatio = i,
            a.config = function(t) {
                return new n(t)
            }
            ,
            n
        }, f = d("Back", p("BackOut", function(t) {
            return (t -= 1) * t * ((this._p1 + 1) * t + this._p1) + 1
        }), p("BackIn", function(t) {
            return t * t * ((this._p1 + 1) * t - this._p1)
        }), p("BackInOut", function(t) {
            return 1 > (t *= 2) ? .5 * t * t * ((this._p2 + 1) * t - this._p2) : .5 * ((t -= 2) * t * ((this._p2 + 1) * t + this._p2) + 2)
        })), m = l("easing.SlowMo", function(t, e, i) {
            e = e || 0 === e ? e : .7,
            null == t ? t = .7 : t > 1 && (t = 1),
            this._p = 1 !== t ? e : 0,
            this._p1 = (1 - t) / 2,
            this._p2 = t,
            this._p3 = this._p1 + this._p2,
            this._calcEnd = i === !0
        }, !0), g = m.prototype = new t;
        return g.constructor = m,
        g.getRatio = function(t) {
            var e = t + (.5 - t) * this._p;
            return this._p1 > t ? this._calcEnd ? 1 - (t = 1 - t / this._p1) * t : e - (t = 1 - t / this._p1) * t * t * t * e : t > this._p3 ? this._calcEnd ? 1 - (t = (t - this._p3) / this._p1) * t : e + (t - e) * (t = (t - this._p3) / this._p1) * t * t * t : this._calcEnd ? 1 : e
        }
        ,
        m.ease = new m(.7,.7),
        g.config = m.config = function(t, e, i) {
            return new m(t,e,i)
        }
        ,
        e = l("easing.SteppedEase", function(t) {
            t = t || 1,
            this._p1 = 1 / t,
            this._p2 = t + 1
        }, !0),
        g = e.prototype = new t,
        g.constructor = e,
        g.getRatio = function(t) {
            return 0 > t ? t = 0 : t >= 1 && (t = .999999999),
            (this._p2 * t >> 0) * this._p1
        }
        ,
        g.config = e.config = function(t) {
            return new e(t)
        }
        ,
        i = l("easing.RoughEase", function(e) {
            e = e || {};
            for (var i, n, a, r, o, s, l = e.taper || "none", c = [], u = 0, d = 0 | (e.points || 20), p = d, f = e.randomize !== !1, m = e.clamp === !0, g = e.template instanceof t ? e.template : null, v = "number" == typeof e.strength ? .4 * e.strength : .4; --p > -1; )
                i = f ? Math.random() : 1 / d * p,
                n = g ? g.getRatio(i) : i,
                "none" === l ? a = v : "out" === l ? (r = 1 - i,
                a = r * r * v) : "in" === l ? a = i * i * v : .5 > i ? (r = 2 * i,
                a = .5 * r * r * v) : (r = 2 * (1 - i),
                a = .5 * r * r * v),
                f ? n += Math.random() * a - .5 * a : p % 2 ? n += .5 * a : n -= .5 * a,
                m && (n > 1 ? n = 1 : 0 > n && (n = 0)),
                c[u++] = {
                    x: i,
                    y: n
                };
            for (c.sort(function(t, e) {
                return t.x - e.x
            }),
            s = new h(1,1,null),
            p = d; --p > -1; )
                o = c[p],
                s = new h(o.x,o.y,s);
            this._prev = new h(0,0,0 !== s.t ? s : s.next)
        }, !0),
        g = i.prototype = new t,
        g.constructor = i,
        g.getRatio = function(t) {
            var e = this._prev;
            if (t > e.t) {
                for (; e.next && t >= e.t; )
                    e = e.next;
                e = e.prev
            } else
                for (; e.prev && e.t >= t; )
                    e = e.prev;
            return this._prev = e,
            e.v + (t - e.t) / e.gap * e.c
        }
        ,
        g.config = function(t) {
            return new i(t)
        }
        ,
        i.ease = new i,
        d("Bounce", c("BounceOut", function(t) {
            return 1 / 2.75 > t ? 7.5625 * t * t : 2 / 2.75 > t ? 7.5625 * (t -= 1.5 / 2.75) * t + .75 : 2.5 / 2.75 > t ? 7.5625 * (t -= 2.25 / 2.75) * t + .9375 : 7.5625 * (t -= 2.625 / 2.75) * t + .984375
        }), c("BounceIn", function(t) {
            return 1 / 2.75 > (t = 1 - t) ? 1 - 7.5625 * t * t : 2 / 2.75 > t ? 1 - (7.5625 * (t -= 1.5 / 2.75) * t + .75) : 2.5 / 2.75 > t ? 1 - (7.5625 * (t -= 2.25 / 2.75) * t + .9375) : 1 - (7.5625 * (t -= 2.625 / 2.75) * t + .984375)
        }), c("BounceInOut", function(t) {
            var e = .5 > t;
            return t = e ? 1 - 2 * t : 2 * t - 1,
            t = 1 / 2.75 > t ? 7.5625 * t * t : 2 / 2.75 > t ? 7.5625 * (t -= 1.5 / 2.75) * t + .75 : 2.5 / 2.75 > t ? 7.5625 * (t -= 2.25 / 2.75) * t + .9375 : 7.5625 * (t -= 2.625 / 2.75) * t + .984375,
            e ? .5 * (1 - t) : .5 * t + .5
        })),
        d("Circ", c("CircOut", function(t) {
            return Math.sqrt(1 - (t -= 1) * t)
        }), c("CircIn", function(t) {
            return -(Math.sqrt(1 - t * t) - 1)
        }), c("CircInOut", function(t) {
            return 1 > (t *= 2) ? -.5 * (Math.sqrt(1 - t * t) - 1) : .5 * (Math.sqrt(1 - (t -= 2) * t) + 1)
        })),
        n = function(e, i, n) {
            var a = l("easing." + e, function(t, e) {
                this._p1 = t >= 1 ? t : 1,
                this._p2 = (e || n) / (1 > t ? t : 1),
                this._p3 = this._p2 / o * (Math.asin(1 / this._p1) || 0),
                this._p2 = o / this._p2
            }, !0)
              , r = a.prototype = new t;
            return r.constructor = a,
            r.getRatio = i,
            r.config = function(t, e) {
                return new a(t,e)
            }
            ,
            a
        }
        ,
        d("Elastic", n("ElasticOut", function(t) {
            return this._p1 * Math.pow(2, -10 * t) * Math.sin((t - this._p3) * this._p2) + 1
        }, .3), n("ElasticIn", function(t) {
            return -(this._p1 * Math.pow(2, 10 * (t -= 1)) * Math.sin((t - this._p3) * this._p2))
        }, .3), n("ElasticInOut", function(t) {
            return 1 > (t *= 2) ? -.5 * this._p1 * Math.pow(2, 10 * (t -= 1)) * Math.sin((t - this._p3) * this._p2) : .5 * this._p1 * Math.pow(2, -10 * (t -= 1)) * Math.sin((t - this._p3) * this._p2) + 1
        }, .45)),
        d("Expo", c("ExpoOut", function(t) {
            return 1 - Math.pow(2, -10 * t)
        }), c("ExpoIn", function(t) {
            return Math.pow(2, 10 * (t - 1)) - .001
        }), c("ExpoInOut", function(t) {
            return 1 > (t *= 2) ? .5 * Math.pow(2, 10 * (t - 1)) : .5 * (2 - Math.pow(2, -10 * (t - 1)))
        })),
        d("Sine", c("SineOut", function(t) {
            return Math.sin(t * s)
        }), c("SineIn", function(t) {
            return -Math.cos(t * s) + 1
        }), c("SineInOut", function(t) {
            return -.5 * (Math.cos(Math.PI * t) - 1)
        })),
        l("easing.EaseLookup", {
            find: function(e) {
                return t.map[e]
            }
        }, !0),
        u(a.SlowMo, "SlowMo", "ease,"),
        u(i, "RoughEase", "ease,"),
        u(e, "SteppedEase", "ease,"),
        f
    }, !0)
}),
_gsScope._gsDefine && _gsScope._gsQueue.pop()(),
function(t, e) {
    "use strict";
    var i = t.GreenSockGlobals = t.GreenSockGlobals || t;
    if (!i.TweenLite) {
        var n, a, r, o, s, l = function(t) {
            var e, n = t.split("."), a = i;
            for (e = 0; n.length > e; e++)
                a[n[e]] = a = a[n[e]] || {};
            return a
        }, c = l("com.greensock"), u = 1e-10, d = function(t) {
            var e, i = [], n = t.length;
            for (e = 0; e !== n; i.push(t[e++]))
                ;
            return i
        }, h = function() {}, p = function() {
            var t = Object.prototype.toString
              , e = t.call([]);
            return function(i) {
                return null != i && (i instanceof Array || "object" == typeof i && !!i.push && t.call(i) === e)
            }
        }(), f = {}, m = function(n, a, r, o) {
            this.sc = f[n] ? f[n].sc : [],
            f[n] = this,
            this.gsClass = null,
            this.func = r;
            var s = [];
            this.check = function(c) {
                for (var u, d, h, p, g = a.length, v = g; --g > -1; )
                    (u = f[a[g]] || new m(a[g],[])).gsClass ? (s[g] = u.gsClass,
                    v--) : c && u.sc.push(this);
                if (0 === v && r)
                    for (d = ("com.greensock." + n).split("."),
                    h = d.pop(),
                    p = l(d.join("."))[h] = this.gsClass = r.apply(r, s),
                    o && (i[h] = p,
                    "function" == typeof define && define.amd ? define((t.GreenSockAMDPath ? t.GreenSockAMDPath + "/" : "") + n.split(".").pop(), [], function() {
                        return p
                    }) : n === e && "undefined" != typeof module && module.exports && (module.exports = p)),
                    g = 0; this.sc.length > g; g++)
                        this.sc[g].check()
            }
            ,
            this.check(!0)
        }, g = t._gsDefine = function(t, e, i, n) {
            return new m(t,e,i,n)
        }
        , v = c._class = function(t, e, i) {
            return e = e || function() {}
            ,
            g(t, [], function() {
                return e
            }, i),
            e
        }
        ;
        g.globals = i;
        var _ = [0, 0, 1, 1]
          , y = []
          , b = v("easing.Ease", function(t, e, i, n) {
            this._func = t,
            this._type = i || 0,
            this._power = n || 0,
            this._params = e ? _.concat(e) : _
        }, !0)
          , x = b.map = {}
          , w = b.register = function(t, e, i, n) {
            for (var a, r, o, s, l = e.split(","), u = l.length, d = (i || "easeIn,easeOut,easeInOut").split(","); --u > -1; )
                for (r = l[u],
                a = n ? v("easing." + r, null, !0) : c.easing[r] || {},
                o = d.length; --o > -1; )
                    s = d[o],
                    x[r + "." + s] = x[s + r] = a[s] = t.getRatio ? t : t[s] || new t
        }
        ;
        for (r = b.prototype,
        r._calcEnd = !1,
        r.getRatio = function(t) {
            if (this._func)
                return this._params[0] = t,
                this._func.apply(null, this._params);
            var e = this._type
              , i = this._power
              , n = 1 === e ? 1 - t : 2 === e ? t : .5 > t ? 2 * t : 2 * (1 - t);
            return 1 === i ? n *= n : 2 === i ? n *= n * n : 3 === i ? n *= n * n * n : 4 === i && (n *= n * n * n * n),
            1 === e ? 1 - n : 2 === e ? n : .5 > t ? n / 2 : 1 - n / 2
        }
        ,
        n = ["Linear", "Quad", "Cubic", "Quart", "Quint,Strong"],
        a = n.length; --a > -1; )
            r = n[a] + ",Power" + a,
            w(new b(null,null,1,a), r, "easeOut", !0),
            w(new b(null,null,2,a), r, "easeIn" + (0 === a ? ",easeNone" : "")),
            w(new b(null,null,3,a), r, "easeInOut");
        x.linear = c.easing.Linear.easeIn,
        x.swing = c.easing.Quad.easeInOut;
        var T = v("events.EventDispatcher", function(t) {
            this._listeners = {},
            this._eventTarget = t || this
        });
        r = T.prototype,
        r.addEventListener = function(t, e, i, n, a) {
            a = a || 0;
            var r, l, c = this._listeners[t], u = 0;
            for (null == c && (this._listeners[t] = c = []),
            l = c.length; --l > -1; )
                r = c[l],
                r.c === e && r.s === i ? c.splice(l, 1) : 0 === u && a > r.pr && (u = l + 1);
            c.splice(u, 0, {
                c: e,
                s: i,
                up: n,
                pr: a
            }),
            this !== o || s || o.wake()
        }
        ,
        r.removeEventListener = function(t, e) {
            var i, n = this._listeners[t];
            if (n)
                for (i = n.length; --i > -1; )
                    if (n[i].c === e)
                        return void n.splice(i, 1)
        }
        ,
        r.dispatchEvent = function(t) {
            var e, i, n, a = this._listeners[t];
            if (a)
                for (e = a.length,
                i = this._eventTarget; --e > -1; )
                    n = a[e],
                    n && (n.up ? n.c.call(n.s || i, {
                        type: t,
                        target: i
                    }) : n.c.call(n.s || i))
        }
        ;
        var C = t.requestAnimationFrame
          , S = t.cancelAnimationFrame
          , k = Date.now || function() {
            return (new Date).getTime()
        }
          , $ = k();
        for (n = ["ms", "moz", "webkit", "o"],
        a = n.length; --a > -1 && !C; )
            C = t[n[a] + "RequestAnimationFrame"],
            S = t[n[a] + "CancelAnimationFrame"] || t[n[a] + "CancelRequestAnimationFrame"];
        v("Ticker", function(t, e) {
            var i, n, a, r, l, c = this, d = k(), p = e !== !1 && C, f = 500, m = 33, g = "tick", v = function(t) {
                var e, o, s = k() - $;
                s > f && (d += s - m),
                $ += s,
                c.time = ($ - d) / 1e3,
                e = c.time - l,
                (!i || e > 0 || t === !0) && (c.frame++,
                l += e + (e >= r ? .004 : r - e),
                o = !0),
                t !== !0 && (a = n(v)),
                o && c.dispatchEvent(g)
            };
            T.call(c),
            c.time = c.frame = 0,
            c.tick = function() {
                v(!0)
            }
            ,
            c.lagSmoothing = function(t, e) {
                f = t || 1 / u,
                m = Math.min(e, f, 0)
            }
            ,
            c.sleep = function() {
                null != a && (p && S ? S(a) : clearTimeout(a),
                n = h,
                a = null,
                c === o && (s = !1))
            }
            ,
            c.wake = function() {
                null !== a ? c.sleep() : c.frame > 10 && ($ = k() - f + 5),
                n = 0 === i ? h : p && C ? C : function(t) {
                    return setTimeout(t, 0 | 1e3 * (l - c.time) + 1)
                }
                ,
                c === o && (s = !0),
                v(2)
            }
            ,
            c.fps = function(t) {
                return arguments.length ? (i = t,
                r = 1 / (i || 60),
                l = this.time + r,
                void c.wake()) : i
            }
            ,
            c.useRAF = function(t) {
                return arguments.length ? (c.sleep(),
                p = t,
                void c.fps(i)) : p
            }
            ,
            c.fps(t),
            setTimeout(function() {
                p && 5 > c.frame && c.useRAF(!1)
            }, 1500)
        }),
        r = c.Ticker.prototype = new c.events.EventDispatcher,
        r.constructor = c.Ticker;
        var D = v("core.Animation", function(t, e) {
            if (this.vars = e = e || {},
            this._duration = this._totalDuration = t || 0,
            this._delay = Number(e.delay) || 0,
            this._timeScale = 1,
            this._active = e.immediateRender === !0,
            this.data = e.data,
            this._reversed = e.reversed === !0,
            H) {
                s || o.wake();
                var i = this.vars.useFrames ? z : H;
                i.add(this, i._time),
                this.vars.paused && this.paused(!0)
            }
        });
        o = D.ticker = new c.Ticker,
        r = D.prototype,
        r._dirty = r._gc = r._initted = r._paused = !1,
        r._totalTime = r._time = 0,
        r._rawPrevTime = -1,
        r._next = r._last = r._onUpdate = r._timeline = r.timeline = null,
        r._paused = !1;
        var A = function() {
            s && k() - $ > 2e3 && o.wake(),
            setTimeout(A, 2e3)
        };
        A(),
        r.play = function(t, e) {
            return null != t && this.seek(t, e),
            this.reversed(!1).paused(!1)
        }
        ,
        r.pause = function(t, e) {
            return null != t && this.seek(t, e),
            this.paused(!0)
        }
        ,
        r.resume = function(t, e) {
            return null != t && this.seek(t, e),
            this.paused(!1)
        }
        ,
        r.seek = function(t, e) {
            return this.totalTime(Number(t), e !== !1)
        }
        ,
        r.restart = function(t, e) {
            return this.reversed(!1).paused(!1).totalTime(t ? -this._delay : 0, e !== !1, !0)
        }
        ,
        r.reverse = function(t, e) {
            return null != t && this.seek(t || this.totalDuration(), e),
            this.reversed(!0).paused(!1)
        }
        ,
        r.render = function() {}
        ,
        r.invalidate = function() {
            return this._time = this._totalTime = 0,
            this._initted = this._gc = !1,
            this._rawPrevTime = -1,
            (this._gc || !this.timeline) && this._enabled(!0),
            this
        }
        ,
        r.isActive = function() {
            var t, e = this._timeline, i = this._startTime;
            return !e || !this._gc && !this._paused && e.isActive() && (t = e.rawTime()) >= i && i + this.totalDuration() / this._timeScale > t
        }
        ,
        r._enabled = function(t, e) {
            return s || o.wake(),
            this._gc = !t,
            this._active = this.isActive(),
            e !== !0 && (t && !this.timeline ? this._timeline.add(this, this._startTime - this._delay) : !t && this.timeline && this._timeline._remove(this, !0)),
            !1
        }
        ,
        r._kill = function() {
            return this._enabled(!1, !1)
        }
        ,
        r.kill = function(t, e) {
            return this._kill(t, e),
            this
        }
        ,
        r._uncache = function(t) {
            for (var e = t ? this : this.timeline; e; )
                e._dirty = !0,
                e = e.timeline;
            return this
        }
        ,
        r._swapSelfInParams = function(t) {
            for (var e = t.length, i = t.concat(); --e > -1; )
                "{self}" === t[e] && (i[e] = this);
            return i
        }
        ,
        r._callback = function(t) {
            var e = this.vars;
            e[t].apply(e[t + "Scope"] || e.callbackScope || this, e[t + "Params"] || y)
        }
        ,
        r.eventCallback = function(t, e, i, n) {
            if ("on" === (t || "").substr(0, 2)) {
                var a = this.vars;
                if (1 === arguments.length)
                    return a[t];
                null == e ? delete a[t] : (a[t] = e,
                a[t + "Params"] = p(i) && -1 !== i.join("").indexOf("{self}") ? this._swapSelfInParams(i) : i,
                a[t + "Scope"] = n),
                "onUpdate" === t && (this._onUpdate = e)
            }
            return this
        }
        ,
        r.delay = function(t) {
            return arguments.length ? (this._timeline.smoothChildTiming && this.startTime(this._startTime + t - this._delay),
            this._delay = t,
            this) : this._delay
        }
        ,
        r.duration = function(t) {
            return arguments.length ? (this._duration = this._totalDuration = t,
            this._uncache(!0),
            this._timeline.smoothChildTiming && this._time > 0 && this._time < this._duration && 0 !== t && this.totalTime(this._totalTime * (t / this._duration), !0),
            this) : (this._dirty = !1,
            this._duration)
        }
        ,
        r.totalDuration = function(t) {
            return this._dirty = !1,
            arguments.length ? this.duration(t) : this._totalDuration
        }
        ,
        r.time = function(t, e) {
            return arguments.length ? (this._dirty && this.totalDuration(),
            this.totalTime(t > this._duration ? this._duration : t, e)) : this._time
        }
        ,
        r.totalTime = function(t, e, i) {
            if (s || o.wake(),
            !arguments.length)
                return this._totalTime;
            if (this._timeline) {
                if (0 > t && !i && (t += this.totalDuration()),
                this._timeline.smoothChildTiming) {
                    this._dirty && this.totalDuration();
                    var n = this._totalDuration
                      , a = this._timeline;
                    if (t > n && !i && (t = n),
                    this._startTime = (this._paused ? this._pauseTime : a._time) - (this._reversed ? n - t : t) / this._timeScale,
                    a._dirty || this._uncache(!1),
                    a._timeline)
                        for (; a._timeline; )
                            a._timeline._time !== (a._startTime + a._totalTime) / a._timeScale && a.totalTime(a._totalTime, !0),
                            a = a._timeline
                }
                this._gc && this._enabled(!0, !1),
                (this._totalTime !== t || 0 === this._duration) && (this.render(t, e, !1),
                M.length && q())
            }
            return this
        }
        ,
        r.progress = r.totalProgress = function(t, e) {
            return arguments.length ? this.totalTime(this.duration() * t, e) : this._time / this.duration()
        }
        ,
        r.startTime = function(t) {
            return arguments.length ? (t !== this._startTime && (this._startTime = t,
            this.timeline && this.timeline._sortChildren && this.timeline.add(this, t - this._delay)),
            this) : this._startTime
        }
        ,
        r.endTime = function(t) {
            return this._startTime + (0 != t ? this.totalDuration() : this.duration()) / this._timeScale
        }
        ,
        r.timeScale = function(t) {
            if (!arguments.length)
                return this._timeScale;
            if (t = t || u,
            this._timeline && this._timeline.smoothChildTiming) {
                var e = this._pauseTime
                  , i = e || 0 === e ? e : this._timeline.totalTime();
                this._startTime = i - (i - this._startTime) * this._timeScale / t
            }
            return this._timeScale = t,
            this._uncache(!1)
        }
        ,
        r.reversed = function(t) {
            return arguments.length ? (t != this._reversed && (this._reversed = t,
            this.totalTime(this._timeline && !this._timeline.smoothChildTiming ? this.totalDuration() - this._totalTime : this._totalTime, !0)),
            this) : this._reversed
        }
        ,
        r.paused = function(t) {
            if (!arguments.length)
                return this._paused;
            var e, i, n = this._timeline;
            return t != this._paused && n && (s || t || o.wake(),
            e = n.rawTime(),
            i = e - this._pauseTime,
            !t && n.smoothChildTiming && (this._startTime += i,
            this._uncache(!1)),
            this._pauseTime = t ? e : null,
            this._paused = t,
            this._active = this.isActive(),
            !t && 0 !== i && this._initted && this.duration() && this.render(n.smoothChildTiming ? this._totalTime : (e - this._startTime) / this._timeScale, !0, !0)),
            this._gc && !t && this._enabled(!0, !1),
            this
        }
        ;
        var O = v("core.SimpleTimeline", function(t) {
            D.call(this, 0, t),
            this.autoRemoveChildren = this.smoothChildTiming = !0
        });
        r = O.prototype = new D,
        r.constructor = O,
        r.kill()._gc = !1,
        r._first = r._last = r._recent = null,
        r._sortChildren = !1,
        r.add = r.insert = function(t, e) {
            var i, n;
            if (t._startTime = Number(e || 0) + t._delay,
            t._paused && this !== t._timeline && (t._pauseTime = t._startTime + (this.rawTime() - t._startTime) / t._timeScale),
            t.timeline && t.timeline._remove(t, !0),
            t.timeline = t._timeline = this,
            t._gc && t._enabled(!0, !0),
            i = this._last,
            this._sortChildren)
                for (n = t._startTime; i && i._startTime > n; )
                    i = i._prev;
            return i ? (t._next = i._next,
            i._next = t) : (t._next = this._first,
            this._first = t),
            t._next ? t._next._prev = t : this._last = t,
            t._prev = i,
            this._recent = t,
            this._timeline && this._uncache(!0),
            this
        }
        ,
        r._remove = function(t, e) {
            return t.timeline === this && (e || t._enabled(!1, !0),
            t._prev ? t._prev._next = t._next : this._first === t && (this._first = t._next),
            t._next ? t._next._prev = t._prev : this._last === t && (this._last = t._prev),
            t._next = t._prev = t.timeline = null,
            t === this._recent && (this._recent = this._last),
            this._timeline && this._uncache(!0)),
            this
        }
        ,
        r.render = function(t, e, i) {
            var n, a = this._first;
            for (this._totalTime = this._time = this._rawPrevTime = t; a; )
                n = a._next,
                (a._active || t >= a._startTime && !a._paused) && (a._reversed ? a.render((a._dirty ? a.totalDuration() : a._totalDuration) - (t - a._startTime) * a._timeScale, e, i) : a.render((t - a._startTime) * a._timeScale, e, i)),
                a = n
        }
        ,
        r.rawTime = function() {
            return s || o.wake(),
            this._totalTime
        }
        ;
        var E = v("TweenLite", function(e, i, n) {
            if (D.call(this, i, n),
            this.render = E.prototype.render,
            null == e)
                throw "Cannot tween a null target.";
            this.target = e = "string" != typeof e ? e : E.selector(e) || e;
            var a, r, o, s = e.jquery || e.length && e !== t && e[0] && (e[0] === t || e[0].nodeType && e[0].style && !e.nodeType), l = this.vars.overwrite;
            if (this._overwrite = l = null == l ? U[E.defaultOverwrite] : "number" == typeof l ? l >> 0 : U[l],
            (s || e instanceof Array || e.push && p(e)) && "number" != typeof e[0])
                for (this._targets = o = d(e),
                this._propLookup = [],
                this._siblings = [],
                a = 0; o.length > a; a++)
                    r = o[a],
                    r ? "string" != typeof r ? r.length && r !== t && r[0] && (r[0] === t || r[0].nodeType && r[0].style && !r.nodeType) ? (o.splice(a--, 1),
                    this._targets = o = o.concat(d(r))) : (this._siblings[a] = Y(r, this, !1),
                    1 === l && this._siblings[a].length > 1 && V(r, this, null, 1, this._siblings[a])) : (r = o[a--] = E.selector(r),
                    "string" == typeof r && o.splice(a + 1, 1)) : o.splice(a--, 1);
            else
                this._propLookup = {},
                this._siblings = Y(e, this, !1),
                1 === l && this._siblings.length > 1 && V(e, this, null, 1, this._siblings);
            (this.vars.immediateRender || 0 === i && 0 === this._delay && this.vars.immediateRender !== !1) && (this._time = -u,
            this.render(-this._delay))
        }, !0)
          , P = function(e) {
            return e && e.length && e !== t && e[0] && (e[0] === t || e[0].nodeType && e[0].style && !e.nodeType)
        }
          , N = function(t, e) {
            var i, n = {};
            for (i in t)
                F[i] || i in e && "transform" !== i && "x" !== i && "y" !== i && "width" !== i && "height" !== i && "className" !== i && "border" !== i || !(!B[i] || B[i] && B[i]._autoCSS) || (n[i] = t[i],
                delete t[i]);
            t.css = n
        };
        r = E.prototype = new D,
        r.constructor = E,
        r.kill()._gc = !1,
        r.ratio = 0,
        r._firstPT = r._targets = r._overwrittenProps = r._startAt = null,
        r._notifyPluginsOfEnabled = r._lazy = !1,
        E.version = "1.17.0",
        E.defaultEase = r._ease = new b(null,null,1,1),
        E.defaultOverwrite = "auto",
        E.ticker = o,
        E.autoSleep = 120,
        E.lagSmoothing = function(t, e) {
            o.lagSmoothing(t, e)
        }
        ,
        E.selector = t.$ || t.jQuery || function(e) {
            var i = t.$ || t.jQuery;
            return i ? (E.selector = i,
            i(e)) : "undefined" == typeof document ? e : document.querySelectorAll ? document.querySelectorAll(e) : document.getElementById("#" === e.charAt(0) ? e.substr(1) : e)
        }
        ;
        var M = []
          , R = {}
          , I = E._internals = {
            isArray: p,
            isSelector: P,
            lazyTweens: M
        }
          , B = E._plugins = {}
          , L = I.tweenLookup = {}
          , j = 0
          , F = I.reservedProps = {
            ease: 1,
            delay: 1,
            overwrite: 1,
            onComplete: 1,
            onCompleteParams: 1,
            onCompleteScope: 1,
            useFrames: 1,
            runBackwards: 1,
            startAt: 1,
            onUpdate: 1,
            onUpdateParams: 1,
            onUpdateScope: 1,
            onStart: 1,
            onStartParams: 1,
            onStartScope: 1,
            onReverseComplete: 1,
            onReverseCompleteParams: 1,
            onReverseCompleteScope: 1,
            onRepeat: 1,
            onRepeatParams: 1,
            onRepeatScope: 1,
            easeParams: 1,
            yoyo: 1,
            immediateRender: 1,
            repeat: 1,
            repeatDelay: 1,
            data: 1,
            paused: 1,
            reversed: 1,
            autoCSS: 1,
            lazy: 1,
            onOverwrite: 1,
            callbackScope: 1
        }
          , U = {
            none: 0,
            all: 1,
            auto: 2,
            concurrent: 3,
            allOnStart: 4,
            preexisting: 5,
            "true": 1,
            "false": 0
        }
          , z = D._rootFramesTimeline = new O
          , H = D._rootTimeline = new O
          , W = 30
          , q = I.lazyRender = function() {
            var t, e = M.length;
            for (R = {}; --e > -1; )
                t = M[e],
                t && t._lazy !== !1 && (t.render(t._lazy[0], t._lazy[1], !0),
                t._lazy = !1);
            M.length = 0
        }
        ;
        H._startTime = o.time,
        z._startTime = o.frame,
        H._active = z._active = !0,
        setTimeout(q, 1),
        D._updateRoot = E.render = function() {
            var t, e, i;
            if (M.length && q(),
            H.render((o.time - H._startTime) * H._timeScale, !1, !1),
            z.render((o.frame - z._startTime) * z._timeScale, !1, !1),
            M.length && q(),
            o.frame >= W) {
                W = o.frame + (parseInt(E.autoSleep, 10) || 120);
                for (i in L) {
                    for (e = L[i].tweens,
                    t = e.length; --t > -1; )
                        e[t]._gc && e.splice(t, 1);
                    0 === e.length && delete L[i]
                }
                if (i = H._first,
                (!i || i._paused) && E.autoSleep && !z._first && 1 === o._listeners.tick.length) {
                    for (; i && i._paused; )
                        i = i._next;
                    i || o.sleep()
                }
            }
        }
        ,
        o.addEventListener("tick", D._updateRoot);
        var Y = function(t, e, i) {
            var n, a, r = t._gsTweenID;
            if (L[r || (t._gsTweenID = r = "t" + j++)] || (L[r] = {
                target: t,
                tweens: []
            }),
            e && (n = L[r].tweens,
            n[a = n.length] = e,
            i))
                for (; --a > -1; )
                    n[a] === e && n.splice(a, 1);
            return L[r].tweens
        }
          , X = function(t, e, i, n) {
            var a, r, o = t.vars.onOverwrite;
            return o && (a = o(t, e, i, n)),
            o = E.onOverwrite,
            o && (r = o(t, e, i, n)),
            a !== !1 && r !== !1
        }
          , V = function(t, e, i, n, a) {
            var r, o, s, l;
            if (1 === n || n >= 4) {
                for (l = a.length,
                r = 0; l > r; r++)
                    if ((s = a[r]) !== e)
                        s._gc || s._kill(null, t, e) && (o = !0);
                    else if (5 === n)
                        break;
                return o
            }
            var c, d = e._startTime + u, h = [], p = 0, f = 0 === e._duration;
            for (r = a.length; --r > -1; )
                (s = a[r]) === e || s._gc || s._paused || (s._timeline !== e._timeline ? (c = c || G(e, 0, f),
                0 === G(s, c, f) && (h[p++] = s)) : d >= s._startTime && s._startTime + s.totalDuration() / s._timeScale > d && ((f || !s._initted) && 2e-10 >= d - s._startTime || (h[p++] = s)));
            for (r = p; --r > -1; )
                if (s = h[r],
                2 === n && s._kill(i, t, e) && (o = !0),
                2 !== n || !s._firstPT && s._initted) {
                    if (2 !== n && !X(s, e))
                        continue;
                    s._enabled(!1, !1) && (o = !0)
                }
            return o
        }
          , G = function(t, e, i) {
            for (var n = t._timeline, a = n._timeScale, r = t._startTime; n._timeline; ) {
                if (r += n._startTime,
                a *= n._timeScale,
                n._paused)
                    return -100;
                n = n._timeline
            }
            return r /= a,
            r > e ? r - e : i && r === e || !t._initted && 2 * u > r - e ? u : (r += t.totalDuration() / t._timeScale / a) > e + u ? 0 : r - e - u
        };
        r._init = function() {
            var t, e, i, n, a, r = this.vars, o = this._overwrittenProps, s = this._duration, l = !!r.immediateRender, c = r.ease;
            if (r.startAt) {
                this._startAt && (this._startAt.render(-1, !0),
                this._startAt.kill()),
                a = {};
                for (n in r.startAt)
                    a[n] = r.startAt[n];
                if (a.overwrite = !1,
                a.immediateRender = !0,
                a.lazy = l && r.lazy !== !1,
                a.startAt = a.delay = null,
                this._startAt = E.to(this.target, 0, a),
                l)
                    if (this._time > 0)
                        this._startAt = null;
                    else if (0 !== s)
                        return
            } else if (r.runBackwards && 0 !== s)
                if (this._startAt)
                    this._startAt.render(-1, !0),
                    this._startAt.kill(),
                    this._startAt = null;
                else {
                    0 !== this._time && (l = !1),
                    i = {};
                    for (n in r)
                        F[n] && "autoCSS" !== n || (i[n] = r[n]);
                    if (i.overwrite = 0,
                    i.data = "isFromStart",
                    i.lazy = l && r.lazy !== !1,
                    i.immediateRender = l,
                    this._startAt = E.to(this.target, 0, i),
                    l) {
                        if (0 === this._time)
                            return
                    } else
                        this._startAt._init(),
                        this._startAt._enabled(!1),
                        this.vars.immediateRender && (this._startAt = null)
                }
            if (this._ease = c = c ? c instanceof b ? c : "function" == typeof c ? new b(c,r.easeParams) : x[c] || E.defaultEase : E.defaultEase,
            r.easeParams instanceof Array && c.config && (this._ease = c.config.apply(c, r.easeParams)),
            this._easeType = this._ease._type,
            this._easePower = this._ease._power,
            this._firstPT = null,
            this._targets)
                for (t = this._targets.length; --t > -1; )
                    this._initProps(this._targets[t], this._propLookup[t] = {}, this._siblings[t], o ? o[t] : null) && (e = !0);
            else
                e = this._initProps(this.target, this._propLookup, this._siblings, o);
            if (e && E._onPluginEvent("_onInitAllProps", this),
            o && (this._firstPT || "function" != typeof this.target && this._enabled(!1, !1)),
            r.runBackwards)
                for (i = this._firstPT; i; )
                    i.s += i.c,
                    i.c = -i.c,
                    i = i._next;
            this._onUpdate = r.onUpdate,
            this._initted = !0
        }
        ,
        r._initProps = function(e, i, n, a) {
            var r, o, s, l, c, u;
            if (null == e)
                return !1;
            R[e._gsTweenID] && q(),
            this.vars.css || e.style && e !== t && e.nodeType && B.css && this.vars.autoCSS !== !1 && N(this.vars, e);
            for (r in this.vars) {
                if (u = this.vars[r],
                F[r])
                    u && (u instanceof Array || u.push && p(u)) && -1 !== u.join("").indexOf("{self}") && (this.vars[r] = u = this._swapSelfInParams(u, this));
                else if (B[r] && (l = new B[r])._onInitTween(e, this.vars[r], this)) {
                    for (this._firstPT = c = {
                        _next: this._firstPT,
                        t: l,
                        p: "setRatio",
                        s: 0,
                        c: 1,
                        f: !0,
                        n: r,
                        pg: !0,
                        pr: l._priority
                    },
                    o = l._overwriteProps.length; --o > -1; )
                        i[l._overwriteProps[o]] = this._firstPT;
                    (l._priority || l._onInitAllProps) && (s = !0),
                    (l._onDisable || l._onEnable) && (this._notifyPluginsOfEnabled = !0)
                } else
                    this._firstPT = i[r] = c = {
                        _next: this._firstPT,
                        t: e,
                        p: r,
                        f: "function" == typeof e[r],
                        n: r,
                        pg: !1,
                        pr: 0
                    },
                    c.s = c.f ? e[r.indexOf("set") || "function" != typeof e["get" + r.substr(3)] ? r : "get" + r.substr(3)]() : parseFloat(e[r]),
                    c.c = "string" == typeof u && "=" === u.charAt(1) ? parseInt(u.charAt(0) + "1", 10) * Number(u.substr(2)) : Number(u) - c.s || 0;
                c && c._next && (c._next._prev = c)
            }
            return a && this._kill(a, e) ? this._initProps(e, i, n, a) : this._overwrite > 1 && this._firstPT && n.length > 1 && V(e, this, i, this._overwrite, n) ? (this._kill(i, e),
            this._initProps(e, i, n, a)) : (this._firstPT && (this.vars.lazy !== !1 && this._duration || this.vars.lazy && !this._duration) && (R[e._gsTweenID] = !0),
            s)
        }
        ,
        r.render = function(t, e, i) {
            var n, a, r, o, s = this._time, l = this._duration, c = this._rawPrevTime;
            if (t >= l)
                this._totalTime = this._time = l,
                this.ratio = this._ease._calcEnd ? this._ease.getRatio(1) : 1,
                this._reversed || (n = !0,
                a = "onComplete",
                i = i || this._timeline.autoRemoveChildren),
                0 === l && (this._initted || !this.vars.lazy || i) && (this._startTime === this._timeline._duration && (t = 0),
                (0 === t || 0 > c || c === u && "isPause" !== this.data) && c !== t && (i = !0,
                c > u && (a = "onReverseComplete")),
                this._rawPrevTime = o = !e || t || c === t ? t : u);
            else if (1e-7 > t)
                this._totalTime = this._time = 0,
                this.ratio = this._ease._calcEnd ? this._ease.getRatio(0) : 0,
                (0 !== s || 0 === l && c > 0) && (a = "onReverseComplete",
                n = this._reversed),
                0 > t && (this._active = !1,
                0 === l && (this._initted || !this.vars.lazy || i) && (c >= 0 && (c !== u || "isPause" !== this.data) && (i = !0),
                this._rawPrevTime = o = !e || t || c === t ? t : u)),
                this._initted || (i = !0);
            else if (this._totalTime = this._time = t,
            this._easeType) {
                var d = t / l
                  , h = this._easeType
                  , p = this._easePower;
                (1 === h || 3 === h && d >= .5) && (d = 1 - d),
                3 === h && (d *= 2),
                1 === p ? d *= d : 2 === p ? d *= d * d : 3 === p ? d *= d * d * d : 4 === p && (d *= d * d * d * d),
                this.ratio = 1 === h ? 1 - d : 2 === h ? d : .5 > t / l ? d / 2 : 1 - d / 2
            } else
                this.ratio = this._ease.getRatio(t / l);
            if (this._time !== s || i) {
                if (!this._initted) {
                    if (this._init(),
                    !this._initted || this._gc)
                        return;
                    if (!i && this._firstPT && (this.vars.lazy !== !1 && this._duration || this.vars.lazy && !this._duration))
                        return this._time = this._totalTime = s,
                        this._rawPrevTime = c,
                        M.push(this),
                        void (this._lazy = [t, e]);
                    this._time && !n ? this.ratio = this._ease.getRatio(this._time / l) : n && this._ease._calcEnd && (this.ratio = this._ease.getRatio(0 === this._time ? 0 : 1))
                }
                for (this._lazy !== !1 && (this._lazy = !1),
                this._active || !this._paused && this._time !== s && t >= 0 && (this._active = !0),
                0 === s && (this._startAt && (t >= 0 ? this._startAt.render(t, e, i) : a || (a = "_dummyGS")),
                this.vars.onStart && (0 !== this._time || 0 === l) && (e || this._callback("onStart"))),
                r = this._firstPT; r; )
                    r.f ? r.t[r.p](r.c * this.ratio + r.s) : r.t[r.p] = r.c * this.ratio + r.s,
                    r = r._next;
                this._onUpdate && (0 > t && this._startAt && t !== -1e-4 && this._startAt.render(t, e, i),
                e || (this._time !== s || n) && this._callback("onUpdate")),
                a && (!this._gc || i) && (0 > t && this._startAt && !this._onUpdate && t !== -1e-4 && this._startAt.render(t, e, i),
                n && (this._timeline.autoRemoveChildren && this._enabled(!1, !1),
                this._active = !1),
                !e && this.vars[a] && this._callback(a),
                0 === l && this._rawPrevTime === u && o !== u && (this._rawPrevTime = 0))
            }
        }
        ,
        r._kill = function(t, e, i) {
            if ("all" === t && (t = null),
            null == t && (null == e || e === this.target))
                return this._lazy = !1,
                this._enabled(!1, !1);
            e = "string" != typeof e ? e || this._targets || this.target : E.selector(e) || e;
            var n, a, r, o, s, l, c, u, d, h = i && this._time && i._startTime === this._startTime && this._timeline === i._timeline;
            if ((p(e) || P(e)) && "number" != typeof e[0])
                for (n = e.length; --n > -1; )
                    this._kill(t, e[n], i) && (l = !0);
            else {
                if (this._targets) {
                    for (n = this._targets.length; --n > -1; )
                        if (e === this._targets[n]) {
                            s = this._propLookup[n] || {},
                            this._overwrittenProps = this._overwrittenProps || [],
                            a = this._overwrittenProps[n] = t ? this._overwrittenProps[n] || {} : "all";
                            break
                        }
                } else {
                    if (e !== this.target)
                        return !1;
                    s = this._propLookup,
                    a = this._overwrittenProps = t ? this._overwrittenProps || {} : "all"
                }
                if (s) {
                    if (c = t || s,
                    u = t !== a && "all" !== a && t !== s && ("object" != typeof t || !t._tempKill),
                    i && (E.onOverwrite || this.vars.onOverwrite)) {
                        for (r in c)
                            s[r] && (d || (d = []),
                            d.push(r));
                        if ((d || !t) && !X(this, i, e, d))
                            return !1
                    }
                    for (r in c)
                        (o = s[r]) && (h && (o.f ? o.t[o.p](o.s) : o.t[o.p] = o.s,
                        l = !0),
                        o.pg && o.t._kill(c) && (l = !0),
                        o.pg && 0 !== o.t._overwriteProps.length || (o._prev ? o._prev._next = o._next : o === this._firstPT && (this._firstPT = o._next),
                        o._next && (o._next._prev = o._prev),
                        o._next = o._prev = null),
                        delete s[r]),
                        u && (a[r] = 1);
                    !this._firstPT && this._initted && this._enabled(!1, !1)
                }
            }
            return l
        }
        ,
        r.invalidate = function() {
            return this._notifyPluginsOfEnabled && E._onPluginEvent("_onDisable", this),
            this._firstPT = this._overwrittenProps = this._startAt = this._onUpdate = null,
            this._notifyPluginsOfEnabled = this._active = this._lazy = !1,
            this._propLookup = this._targets ? {} : [],
            D.prototype.invalidate.call(this),
            this.vars.immediateRender && (this._time = -u,
            this.render(-this._delay)),
            this
        }
        ,
        r._enabled = function(t, e) {
            if (s || o.wake(),
            t && this._gc) {
                var i, n = this._targets;
                if (n)
                    for (i = n.length; --i > -1; )
                        this._siblings[i] = Y(n[i], this, !0);
                else
                    this._siblings = Y(this.target, this, !0)
            }
            return D.prototype._enabled.call(this, t, e),
            this._notifyPluginsOfEnabled && this._firstPT ? E._onPluginEvent(t ? "_onEnable" : "_onDisable", this) : !1
        }
        ,
        E.to = function(t, e, i) {
            return new E(t,e,i)
        }
        ,
        E.from = function(t, e, i) {
            return i.runBackwards = !0,
            i.immediateRender = 0 != i.immediateRender,
            new E(t,e,i)
        }
        ,
        E.fromTo = function(t, e, i, n) {
            return n.startAt = i,
            n.immediateRender = 0 != n.immediateRender && 0 != i.immediateRender,
            new E(t,e,n)
        }
        ,
        E.delayedCall = function(t, e, i, n, a) {
            return new E(e,0,{
                delay: t,
                onComplete: e,
                onCompleteParams: i,
                callbackScope: n,
                onReverseComplete: e,
                onReverseCompleteParams: i,
                immediateRender: !1,
                lazy: !1,
                useFrames: a,
                overwrite: 0
            })
        }
        ,
        E.set = function(t, e) {
            return new E(t,0,e)
        }
        ,
        E.getTweensOf = function(t, e) {
            if (null == t)
                return [];
            t = "string" != typeof t ? t : E.selector(t) || t;
            var i, n, a, r;
            if ((p(t) || P(t)) && "number" != typeof t[0]) {
                for (i = t.length,
                n = []; --i > -1; )
                    n = n.concat(E.getTweensOf(t[i], e));
                for (i = n.length; --i > -1; )
                    for (r = n[i],
                    a = i; --a > -1; )
                        r === n[a] && n.splice(i, 1)
            } else
                for (n = Y(t).concat(),
                i = n.length; --i > -1; )
                    (n[i]._gc || e && !n[i].isActive()) && n.splice(i, 1);
            return n
        }
        ,
        E.killTweensOf = E.killDelayedCallsTo = function(t, e, i) {
            "object" == typeof e && (i = e,
            e = !1);
            for (var n = E.getTweensOf(t, e), a = n.length; --a > -1; )
                n[a]._kill(i, t)
        }
        ;
        var Q = v("plugins.TweenPlugin", function(t, e) {
            this._overwriteProps = (t || "").split(","),
            this._propName = this._overwriteProps[0],
            this._priority = e || 0,
            this._super = Q.prototype
        }, !0);
        if (r = Q.prototype,
        Q.version = "1.10.1",
        Q.API = 2,
        r._firstPT = null,
        r._addTween = function(t, e, i, n, a, r) {
            var o, s;
            return null != n && (o = "number" == typeof n || "=" !== n.charAt(1) ? Number(n) - Number(i) : parseInt(n.charAt(0) + "1", 10) * Number(n.substr(2))) ? (this._firstPT = s = {
                _next: this._firstPT,
                t: t,
                p: e,
                s: i,
                c: o,
                f: "function" == typeof t[e],
                n: a || e,
                r: r
            },
            s._next && (s._next._prev = s),
            s) : void 0
        }
        ,
        r.setRatio = function(t) {
            for (var e, i = this._firstPT, n = 1e-6; i; )
                e = i.c * t + i.s,
                i.r ? e = Math.round(e) : n > e && e > -n && (e = 0),
                i.f ? i.t[i.p](e) : i.t[i.p] = e,
                i = i._next
        }
        ,
        r._kill = function(t) {
            var e, i = this._overwriteProps, n = this._firstPT;
            if (null != t[this._propName])
                this._overwriteProps = [];
            else
                for (e = i.length; --e > -1; )
                    null != t[i[e]] && i.splice(e, 1);
            for (; n; )
                null != t[n.n] && (n._next && (n._next._prev = n._prev),
                n._prev ? (n._prev._next = n._next,
                n._prev = null) : this._firstPT === n && (this._firstPT = n._next)),
                n = n._next;
            return !1
        }
        ,
        r._roundProps = function(t, e) {
            for (var i = this._firstPT; i; )
                (t[this._propName] || null != i.n && t[i.n.split(this._propName + "_").join("")]) && (i.r = e),
                i = i._next
        }
        ,
        E._onPluginEvent = function(t, e) {
            var i, n, a, r, o, s = e._firstPT;
            if ("_onInitAllProps" === t) {
                for (; s; ) {
                    for (o = s._next,
                    n = a; n && n.pr > s.pr; )
                        n = n._next;
                    (s._prev = n ? n._prev : r) ? s._prev._next = s : a = s,
                    (s._next = n) ? n._prev = s : r = s,
                    s = o
                }
                s = e._firstPT = a
            }
            for (; s; )
                s.pg && "function" == typeof s.t[t] && s.t[t]() && (i = !0),
                s = s._next;
            return i
        }
        ,
        Q.activate = function(t) {
            for (var e = t.length; --e > -1; )
                t[e].API === Q.API && (B[(new t[e])._propName] = t[e]);
            return !0
        }
        ,
        g.plugin = function(t) {
            if (!(t && t.propName && t.init && t.API))
                throw "illegal plugin definition.";
            var e, i = t.propName, n = t.priority || 0, a = t.overwriteProps, r = {
                init: "_onInitTween",
                set: "setRatio",
                kill: "_kill",
                round: "_roundProps",
                initAll: "_onInitAllProps"
            }, o = v("plugins." + i.charAt(0).toUpperCase() + i.substr(1) + "Plugin", function() {
                Q.call(this, i, n),
                this._overwriteProps = a || []
            }, t.global === !0), s = o.prototype = new Q(i);
            s.constructor = o,
            o.API = t.API;
            for (e in r)
                "function" == typeof t[e] && (s[r[e]] = t[e]);
            return o.version = t.version,
            Q.activate([o]),
            o
        }
        ,
        n = t._gsQueue) {
            for (a = 0; n.length > a; a++)
                n[a]();
            for (r in f)
                f[r].func || t.console.log("GSAP encountered missing dependency: com.greensock." + r)
        }
        s = !1
    }
}("undefined" != typeof module && module.exports && "undefined" != typeof global ? global : this || window, "TweenMax"),
function(t) {
    "use strict";
    var e, i = t.Base64, n = "2.1.9";
    if ("undefined" != typeof module && module.exports)
        try {
            e = require("buffer").Buffer
        } catch (a) {}
    var r = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/"
      , o = function(t) {
        for (var e = {}, i = 0, n = t.length; n > i; i++)
            e[t.charAt(i)] = i;
        return e
    }(r)
      , s = String.fromCharCode
      , l = function(t) {
        if (t.length < 2) {
            var e = t.charCodeAt(0);
            return 128 > e ? t : 2048 > e ? s(192 | e >>> 6) + s(128 | 63 & e) : s(224 | e >>> 12 & 15) + s(128 | e >>> 6 & 63) + s(128 | 63 & e)
        }
        var e = 65536 + 1024 * (t.charCodeAt(0) - 55296) + (t.charCodeAt(1) - 56320);
        return s(240 | e >>> 18 & 7) + s(128 | e >>> 12 & 63) + s(128 | e >>> 6 & 63) + s(128 | 63 & e)
    }
      , c = /[\uD800-\uDBFF][\uDC00-\uDFFFF]|[^\x00-\x7F]/g
      , u = function(t) {
        return t.replace(c, l)
    }
      , d = function(t) {
        var e = [0, 2, 1][t.length % 3]
          , i = t.charCodeAt(0) << 16 | (t.length > 1 ? t.charCodeAt(1) : 0) << 8 | (t.length > 2 ? t.charCodeAt(2) : 0)
          , n = [r.charAt(i >>> 18), r.charAt(i >>> 12 & 63), e >= 2 ? "=" : r.charAt(i >>> 6 & 63), e >= 1 ? "=" : r.charAt(63 & i)];
        return n.join("")
    }
      , h = t.btoa ? function(e) {
        return t.btoa(e)
    }
    : function(t) {
        return t.replace(/[\s\S]{1,3}/g, d)
    }
      , p = e ? function(t) {
        return (t.constructor === e.constructor ? t : new e(t)).toString("base64")
    }
    : function(t) {
        return h(u(t))
    }
      , f = function(t, e) {
        return e ? p(String(t)).replace(/[+\/]/g, function(t) {
            return "+" == t ? "-" : "_"
        }).replace(/=/g, "") : p(String(t))
    }
      , m = function(t) {
        return f(t, !0)
    }
      , g = new RegExp(["[À-ß][-¿]", "[à-ï][-¿]{2}", "[ð-÷][-¿]{3}"].join("|"),"g")
      , v = function(t) {
        switch (t.length) {
        case 4:
            var e = (7 & t.charCodeAt(0)) << 18 | (63 & t.charCodeAt(1)) << 12 | (63 & t.charCodeAt(2)) << 6 | 63 & t.charCodeAt(3)
              , i = e - 65536;
            return s((i >>> 10) + 55296) + s((1023 & i) + 56320);
        case 3:
            return s((15 & t.charCodeAt(0)) << 12 | (63 & t.charCodeAt(1)) << 6 | 63 & t.charCodeAt(2));
        default:
            return s((31 & t.charCodeAt(0)) << 6 | 63 & t.charCodeAt(1))
        }
    }
      , _ = function(t) {
        return t.replace(g, v)
    }
      , y = function(t) {
        var e = t.length
          , i = e % 4
          , n = (e > 0 ? o[t.charAt(0)] << 18 : 0) | (e > 1 ? o[t.charAt(1)] << 12 : 0) | (e > 2 ? o[t.charAt(2)] << 6 : 0) | (e > 3 ? o[t.charAt(3)] : 0)
          , a = [s(n >>> 16), s(n >>> 8 & 255), s(255 & n)];
        return a.length -= [0, 0, 2, 1][i],
        a.join("")
    }
      , b = t.atob ? function(e) {
        return t.atob(e)
    }
    : function(t) {
        return t.replace(/[\s\S]{1,4}/g, y)
    }
      , x = e ? function(t) {
        return (t.constructor === e.constructor ? t : new e(t,"base64")).toString()
    }
    : function(t) {
        return _(b(t))
    }
      , w = function(t) {
        return x(String(t).replace(/[-_]/g, function(t) {
            return "-" == t ? "+" : "/"
        }).replace(/[^A-Za-z0-9\+\/]/g, ""))
    }
      , T = function() {
        var e = t.Base64;
        return t.Base64 = i,
        e
    };
    if (t.Base64 = {
        VERSION: n,
        atob: b,
        btoa: h,
        fromBase64: w,
        toBase64: f,
        utob: u,
        encode: f,
        encodeURI: m,
        btou: _,
        decode: w,
        noConflict: T
    },
    "function" == typeof Object.defineProperty) {
        var C = function(t) {
            return {
                value: t,
                enumerable: !1,
                writable: !0,
                configurable: !0
            }
        };
        t.Base64.extendString = function() {
            Object.defineProperty(String.prototype, "fromBase64", C(function() {
                return w(this)
            })),
            Object.defineProperty(String.prototype, "toBase64", C(function(t) {
                return f(this, t)
            })),
            Object.defineProperty(String.prototype, "toBase64URI", C(function() {
                return f(this, !0)
            }))
        }
    }
    t.Meteor && (Base64 = t.Base64)
}(this),
function(t) {
    "use strict";
    var e, i, n, a = t.fn.animate, r = t.fn.stop, o = !0, s = function(t) {
        var e, i = {};
        for (e in t)
            i[e] = t[e];
        return i
    }, l = {
        overwrite: 1,
        delay: 1,
        useFrames: 1,
        runBackwards: 1,
        easeParams: 1,
        yoyo: 1,
        immediateRender: 1,
        repeat: 1,
        repeatDelay: 1,
        autoCSS: 1
    }, c = ",scrollTop,scrollLeft,show,hide,toggle,", u = c, d = function(t, e) {
        for (var i in l)
            l[i] && void 0 !== t[i] && (e[i] = t[i])
    }, h = function(t) {
        return function(e) {
            return t.getRatio(e)
        }
    }, p = {}, f = function() {
        var a, r, o, s = window.GreenSockGlobals || window;
        if (e = s.TweenMax || s.TweenLite,
        e && (a = (e.version + ".0.0").split("."),
        r = !(Number(a[0]) > 0 && Number(a[1]) > 7),
        s = s.com.greensock,
        i = s.plugins.CSSPlugin,
        p = s.easing.Ease.map || {}),
        !e || !i || r)
            return e = null,
            void (!n && window.console && (window.console.log("The jquery.gsap.js plugin requires the TweenMax (or at least TweenLite and CSSPlugin) JavaScript file(s)." + (r ? " Version " + a.join(".") + " is too old." : "")),
            n = !0));
        if (t.easing) {
            for (o in p)
                t.easing[o] = h(p[o]);
            f = !1
        }
    };
    t.fn.animate = function(n, r, l, c) {
        if (n = n || {},
        f && (f(),
        !e || !i))
            return a.call(this, n, r, l, c);
        if (!o || n.skipGSAP === !0 || "object" == typeof r && "function" == typeof r.step)
            return a.call(this, n, r, l, c);
        var h, m, g, v, _ = t.speed(r, l, c), y = {
            ease: p[_.easing] || (_.easing === !1 ? p.linear : p.swing)
        }, b = this, x = "object" == typeof r ? r.specialEasing : null;
        for (m in n) {
            if (h = n[m],
            h instanceof Array && p[h[1]] && (x = x || {},
            x[m] = h[1],
            h = h[0]),
            "show" === h || "hide" === h || "toggle" === h || -1 !== u.indexOf(m) && -1 !== u.indexOf("," + m + ","))
                return a.call(this, n, r, l, c);
            y[-1 === m.indexOf("-") ? m : t.camelCase(m)] = h
        }
        if (x) {
            y = s(y),
            v = [];
            for (m in x)
                h = v[v.length] = {},
                d(y, h),
                h.ease = p[x[m]] || y.ease,
                -1 !== m.indexOf("-") && (m = t.camelCase(m)),
                h[m] = y[m],
                delete y[m];
            0 === v.length && (v = null)
        }
        return g = function(i) {
            var n, a = s(y);
            if (v)
                for (n = v.length; --n > -1; )
                    e.to(this, t.fx.off ? 0 : _.duration / 1e3, v[n]);
            a.onComplete = function() {
                i ? i() : _.old && t(this).each(_.old)
            }
            ,
            e.to(this, t.fx.off ? 0 : _.duration / 1e3, a)
        }
        ,
        _.queue !== !1 ? (b.queue(_.queue, g),
        "function" == typeof _.old && b.queue(_.queue, function(t) {
            _.old.call(this),
            t()
        })) : g.call(b),
        b
    }
    ,
    t.fn.stop = function(t, i) {
        if (r.call(this, t, i),
        e) {
            if (i)
                for (var n, a = e.getTweensOf(this), o = a.length; --o > -1; )
                    n = a[o].totalTime() / a[o].totalDuration(),
                    n > 0 && 1 > n && a[o].seek(a[o].totalDuration());
            e.killTweensOf(this)
        }
        return this
    }
    ,
    t.gsap = {
        enabled: function(t) {
            o = t
        },
        version: "0.1.11",
        legacyProps: function(t) {
            u = c + t + ","
        }
    }
}(jQuery),
$(document).ready(function() {
    history.pushState && (ajxData = {
        href: String(window.location)
    },
    history.replaceState(ajxData, document.title, ajxData.href))
}),
jQuery.fn.easyAjaxObject = function(t) {
    $(document).on("click", $(this), function(e) {
        t.url = $(this).attr("href"),
        e.preventDefault()
    })
}
;
var easyAjax = function(t) {
    function e() {
        i(),
        "function" == typeof t.preAjaxFunc && t.preAjaxFunc(),
        n()
    }
    function i() {
        $.each(t, function(t, e) {
            a[t] = e
        })
    }
    function n() {
        $.ajax({
            url: a.url,
            method: "GET",
            headers: a.headers,
            data: a.data
        }).done(function(e) {
            $.each(a.targets, function(t, i) {
                $(t).html($(e).find(i).html())
            }),
            ajxData = {
                href: String(a.url)
            },
            history.pushState && history.pushState(ajxData, document.title, a.url),
            "function" == typeof t.postAjaxFunc && t.postAjaxFunc()
			
			$(e).each(function(index, item){
				if(item.nodeName.toLowerCase() == "title")
					document.title = item.text;
			})
			
        })
    }
    var a = {};
    e()
};
if (window.onpopstate = function(t) {
    window.location = t.state.href
}
,
"undefined" == typeof jQuery)
    throw new Error("Bootstrap's JavaScript requires jQuery");
+function(t) {
    "use strict";
    var e = t.fn.jquery.split(" ")[0].split(".");
    if (e[0] < 2 && e[1] < 9 || 1 == e[0] && 9 == e[1] && e[2] < 1)
        throw new Error("Bootstrap's JavaScript requires jQuery version 1.9.1 or higher")
}(jQuery),
+function(t) {
    "use strict";
    function e() {
        var t = document.createElement("bootstrap")
          , e = {
            WebkitTransition: "webkitTransitionEnd",
            MozTransition: "transitionend",
            OTransition: "oTransitionEnd otransitionend",
            transition: "transitionend"
        };
        for (var i in e)
            if (void 0 !== t.style[i])
                return {
                    end: e[i]
                };
        return !1
    }
    t.fn.emulateTransitionEnd = function(e) {
        var i = !1
          , n = this;
        t(this).one("bsTransitionEnd", function() {
            i = !0
        });
        var a = function() {
            i || t(n).trigger(t.support.transition.end)
        };
        return setTimeout(a, e),
        this
    }
    ,
    t(function() {
        t.support.transition = e(),
        t.support.transition && (t.event.special.bsTransitionEnd = {
            bindType: t.support.transition.end,
            delegateType: t.support.transition.end,
            handle: function(e) {
                return t(e.target).is(this) ? e.handleObj.handler.apply(this, arguments) : void 0
            }
        })
    })
}(jQuery),
+function(t) {
    "use strict";
    function e(e) {
        return this.each(function() {
            var i = t(this)
              , a = i.data("bs.alert");
            a || i.data("bs.alert", a = new n(this)),
            "string" == typeof e && a[e].call(i)
        })
    }
    var i = '[data-dismiss="alert"]'
      , n = function(e) {
        t(e).on("click", i, this.close)
    };
    n.VERSION = "3.3.5",
    n.TRANSITION_DURATION = 150,
    n.prototype.close = function(e) {
        function i() {
            o.detach().trigger("closed.bs.alert").remove()
        }
        var a = t(this)
          , r = a.attr("data-target");
        r || (r = a.attr("href"),
        r = r && r.replace(/.*(?=#[^\s]*$)/, ""));
        var o = t(r);
        e && e.preventDefault(),
        o.length || (o = a.closest(".alert")),
        o.trigger(e = t.Event("close.bs.alert")),
        e.isDefaultPrevented() || (o.removeClass("in"),
        t.support.transition && o.hasClass("fade") ? o.one("bsTransitionEnd", i).emulateTransitionEnd(n.TRANSITION_DURATION) : i())
    }
    ;
    var a = t.fn.alert;
    t.fn.alert = e,
    t.fn.alert.Constructor = n,
    t.fn.alert.noConflict = function() {
        return t.fn.alert = a,
        this
    }
    ,
    t(document).on("click.bs.alert.data-api", i, n.prototype.close)
}(jQuery),
+function(t) {
    "use strict";
    function e(e) {
        return this.each(function() {
            var n = t(this)
              , a = n.data("bs.button")
              , r = "object" == typeof e && e;
            a || n.data("bs.button", a = new i(this,r)),
            "toggle" == e ? a.toggle() : e && a.setState(e)
        })
    }
    var i = function(e, n) {
        this.$element = t(e),
        this.options = t.extend({}, i.DEFAULTS, n),
        this.isLoading = !1
    };
    i.VERSION = "3.3.5",
    i.DEFAULTS = {
        loadingText: "loading..."
    },
    i.prototype.setState = function(e) {
        var i = "disabled"
          , n = this.$element
          , a = n.is("input") ? "val" : "html"
          , r = n.data();
        e += "Text",
        null == r.resetText && n.data("resetText", n[a]()),
        setTimeout(t.proxy(function() {
            n[a](null == r[e] ? this.options[e] : r[e]),
            "loadingText" == e ? (this.isLoading = !0,
            n.addClass(i).attr(i, i)) : this.isLoading && (this.isLoading = !1,
            n.removeClass(i).removeAttr(i))
        }, this), 0)
    }
    ,
    i.prototype.toggle = function() {
        var t = !0
          , e = this.$element.closest('[data-toggle="buttons"]');
        if (e.length) {
            var i = this.$element.find("input");
            "radio" == i.prop("type") ? (i.prop("checked") && (t = !1),
            e.find(".active").removeClass("active"),
            this.$element.addClass("active")) : "checkbox" == i.prop("type") && (i.prop("checked") !== this.$element.hasClass("active") && (t = !1),
            this.$element.toggleClass("active")),
            i.prop("checked", this.$element.hasClass("active")),
            t && i.trigger("change")
        } else
            this.$element.attr("aria-pressed", !this.$element.hasClass("active")),
            this.$element.toggleClass("active")
    }
    ;
    var n = t.fn.button;
    t.fn.button = e,
    t.fn.button.Constructor = i,
    t.fn.button.noConflict = function() {
        return t.fn.button = n,
        this
    }
    ,
    t(document).on("click.bs.button.data-api", '[data-toggle^="button"]', function(i) {
        var n = t(i.target);
        n.hasClass("btn") || (n = n.closest(".btn")),
        e.call(n, "toggle"),
        t(i.target).is('input[type="radio"]') || t(i.target).is('input[type="checkbox"]') || i.preventDefault()
    }).on("focus.bs.button.data-api blur.bs.button.data-api", '[data-toggle^="button"]', function(e) {
        t(e.target).closest(".btn").toggleClass("focus", /^focus(in)?$/.test(e.type))
    })
}(jQuery),
+function(t) {
    "use strict";
    function e(e) {
        return this.each(function() {
            var n = t(this)
              , a = n.data("bs.carousel")
              , r = t.extend({}, i.DEFAULTS, n.data(), "object" == typeof e && e)
              , o = "string" == typeof e ? e : r.slide;
            a || n.data("bs.carousel", a = new i(this,r)),
            "number" == typeof e ? a.to(e) : o ? a[o]() : r.interval && a.pause().cycle()
        })
    }
    var i = function(e, i) {
        this.$element = t(e),
        this.$indicators = this.$element.find(".carousel-indicators"),
        this.options = i,
        this.paused = null,
        this.sliding = null,
        this.interval = null,
        this.$active = null,
        this.$items = null,
        this.options.keyboard && this.$element.on("keydown.bs.carousel", t.proxy(this.keydown, this)),
        "hover" == this.options.pause && !("ontouchstart"in document.documentElement) && this.$element.on("mouseenter.bs.carousel", t.proxy(this.pause, this)).on("mouseleave.bs.carousel", t.proxy(this.cycle, this))
    };
    i.VERSION = "3.3.5",
    i.TRANSITION_DURATION = 600,
    i.DEFAULTS = {
        interval: 5e3,
        pause: "hover",
        wrap: !0,
        keyboard: !0
    },
    i.prototype.keydown = function(t) {
        if (!/input|textarea/i.test(t.target.tagName)) {
            switch (t.which) {
            case 37:
                this.prev();
                break;
            case 39:
                this.next();
                break;
            default:
                return
            }
            t.preventDefault()
        }
    }
    ,
    i.prototype.cycle = function(e) {
        return e || (this.paused = !1),
        this.interval && clearInterval(this.interval),
        this.options.interval && !this.paused && (this.interval = setInterval(t.proxy(this.next, this), this.options.interval)),
        this
    }
    ,
    i.prototype.getItemIndex = function(t) {
        return this.$items = t.parent().children(".item"),
        this.$items.index(t || this.$active)
    }
    ,
    i.prototype.getItemForDirection = function(t, e) {
        var i = this.getItemIndex(e)
          , n = "prev" == t && 0 === i || "next" == t && i == this.$items.length - 1;
        if (n && !this.options.wrap)
            return e;
        var a = "prev" == t ? -1 : 1
          , r = (i + a) % this.$items.length;
        return this.$items.eq(r)
    }
    ,
    i.prototype.to = function(t) {
        var e = this
          , i = this.getItemIndex(this.$active = this.$element.find(".item.active"));
        return t > this.$items.length - 1 || 0 > t ? void 0 : this.sliding ? this.$element.one("slid.bs.carousel", function() {
            e.to(t)
        }) : i == t ? this.pause().cycle() : this.slide(t > i ? "next" : "prev", this.$items.eq(t))
    }
    ,
    i.prototype.pause = function(e) {
        return e || (this.paused = !0),
        this.$element.find(".next, .prev").length && t.support.transition && (this.$element.trigger(t.support.transition.end),
        this.cycle(!0)),
        this.interval = clearInterval(this.interval),
        this
    }
    ,
    i.prototype.next = function() {
        return this.sliding ? void 0 : this.slide("next")
    }
    ,
    i.prototype.prev = function() {
        return this.sliding ? void 0 : this.slide("prev")
    }
    ,
    i.prototype.slide = function(e, n) {
        var a = this.$element.find(".item.active")
          , r = n || this.getItemForDirection(e, a)
          , o = this.interval
          , s = "next" == e ? "left" : "right"
          , l = this;
        if (r.hasClass("active"))
            return this.sliding = !1;
        var c = r[0]
          , u = t.Event("slide.bs.carousel", {
            relatedTarget: c,
            direction: s
        });
        if (this.$element.trigger(u),
        !u.isDefaultPrevented()) {
            if (this.sliding = !0,
            o && this.pause(),
            this.$indicators.length) {
                this.$indicators.find(".active").removeClass("active");
                var d = t(this.$indicators.children()[this.getItemIndex(r)]);
                d && d.addClass("active")
            }
            var h = t.Event("slid.bs.carousel", {
                relatedTarget: c,
                direction: s
            });
            return t.support.transition && this.$element.hasClass("slide") ? (r.addClass(e),
            r[0].offsetWidth,
            a.addClass(s),
            r.addClass(s),
            a.one("bsTransitionEnd", function() {
                r.removeClass([e, s].join(" ")).addClass("active"),
                a.removeClass(["active", s].join(" ")),
                l.sliding = !1,
                setTimeout(function() {
                    l.$element.trigger(h)
                }, 0)
            }).emulateTransitionEnd(i.TRANSITION_DURATION)) : (a.removeClass("active"),
            r.addClass("active"),
            this.sliding = !1,
            this.$element.trigger(h)),
            o && this.cycle(),
            this
        }
    }
    ;
    var n = t.fn.carousel;
    t.fn.carousel = e,
    t.fn.carousel.Constructor = i,
    t.fn.carousel.noConflict = function() {
        return t.fn.carousel = n,
        this
    }
    ;
    var a = function(i) {
        var n, a = t(this), r = t(a.attr("data-target") || (n = a.attr("href")) && n.replace(/.*(?=#[^\s]+$)/, ""));
        if (r.hasClass("carousel")) {
            var o = t.extend({}, r.data(), a.data())
              , s = a.attr("data-slide-to");
            s && (o.interval = !1),
            e.call(r, o),
            s && r.data("bs.carousel").to(s),
            i.preventDefault()
        }
    };
    t(document).on("click.bs.carousel.data-api", "[data-slide]", a).on("click.bs.carousel.data-api", "[data-slide-to]", a),
    t(window).on("load", function() {
        t('[data-ride="carousel"]').each(function() {
            var i = t(this);
            e.call(i, i.data())
        })
    })
}(jQuery),
+function(t) {
    "use strict";
    function e(e) {
        var i, n = e.attr("data-target") || (i = e.attr("href")) && i.replace(/.*(?=#[^\s]+$)/, "");
        return t(n)
    }
    function i(e) {
        return this.each(function() {
            var i = t(this)
              , a = i.data("bs.collapse")
              , r = t.extend({}, n.DEFAULTS, i.data(), "object" == typeof e && e);
            !a && r.toggle && /show|hide/.test(e) && (r.toggle = !1),
            a || i.data("bs.collapse", a = new n(this,r)),
            "string" == typeof e && a[e]()
        })
    }
    var n = function(e, i) {
        this.$element = t(e),
        this.options = t.extend({}, n.DEFAULTS, i),
        this.$trigger = t('[data-toggle="collapse"][href="#' + e.id + '"],[data-toggle="collapse"][data-target="#' + e.id + '"]'),
        this.transitioning = null,
        this.options.parent ? this.$parent = this.getParent() : this.addAriaAndCollapsedClass(this.$element, this.$trigger),
        this.options.toggle && this.toggle()
    };
    n.VERSION = "3.3.5",
    n.TRANSITION_DURATION = 350,
    n.DEFAULTS = {
        toggle: !0
    },
    n.prototype.dimension = function() {
        var t = this.$element.hasClass("width");
        return t ? "width" : "height"
    }
    ,
    n.prototype.show = function() {
        if (!this.transitioning && !this.$element.hasClass("in")) {
            var e, a = this.$parent && this.$parent.children(".panel").children(".in, .collapsing");
            if (!(a && a.length && (e = a.data("bs.collapse"),
            e && e.transitioning))) {
                var r = t.Event("show.bs.collapse");
                if (this.$element.trigger(r),
                !r.isDefaultPrevented()) {
                    a && a.length && (i.call(a, "hide"),
                    e || a.data("bs.collapse", null));
                    var o = this.dimension();
                    this.$element.removeClass("collapse").addClass("collapsing")[o](0).attr("aria-expanded", !0),
                    this.$trigger.removeClass("collapsed").attr("aria-expanded", !0),
                    this.transitioning = 1;
                    var s = function() {
                        this.$element.removeClass("collapsing").addClass("collapse in")[o](""),
                        this.transitioning = 0,
                        this.$element.trigger("shown.bs.collapse")
                    };
                    if (!t.support.transition)
                        return s.call(this);
                    var l = t.camelCase(["scroll", o].join("-"));
                    this.$element.one("bsTransitionEnd", t.proxy(s, this)).emulateTransitionEnd(n.TRANSITION_DURATION)[o](this.$element[0][l])
                }
            }
        }
    }
    ,
    n.prototype.hide = function() {
        if (!this.transitioning && this.$element.hasClass("in")) {
            var e = t.Event("hide.bs.collapse");
            if (this.$element.trigger(e),
            !e.isDefaultPrevented()) {
                var i = this.dimension();
                this.$element[i](this.$element[i]())[0].offsetHeight,
                this.$element.addClass("collapsing").removeClass("collapse in").attr("aria-expanded", !1),
                this.$trigger.addClass("collapsed").attr("aria-expanded", !1),
                this.transitioning = 1;
                var a = function() {
                    this.transitioning = 0,
                    this.$element.removeClass("collapsing").addClass("collapse").trigger("hidden.bs.collapse")
                };
                return t.support.transition ? void this.$element[i](0).one("bsTransitionEnd", t.proxy(a, this)).emulateTransitionEnd(n.TRANSITION_DURATION) : a.call(this)
            }
        }
    }
    ,
    n.prototype.toggle = function() {
        this[this.$element.hasClass("in") ? "hide" : "show"]()
    }
    ,
    n.prototype.getParent = function() {
        return t(this.options.parent).find('[data-toggle="collapse"][data-parent="' + this.options.parent + '"]').each(t.proxy(function(i, n) {
            var a = t(n);
            this.addAriaAndCollapsedClass(e(a), a)
        }, this)).end()
    }
    ,
    n.prototype.addAriaAndCollapsedClass = function(t, e) {
        var i = t.hasClass("in");
        t.attr("aria-expanded", i),
        e.toggleClass("collapsed", !i).attr("aria-expanded", i)
    }
    ;
    var a = t.fn.collapse;
    t.fn.collapse = i,
    t.fn.collapse.Constructor = n,
    t.fn.collapse.noConflict = function() {
        return t.fn.collapse = a,
        this
    }
    ,
    t(document).on("click.bs.collapse.data-api", '[data-toggle="collapse"]', function(n) {
        var a = t(this);
        a.attr("data-target") || n.preventDefault();
        var r = e(a)
          , o = r.data("bs.collapse")
          , s = o ? "toggle" : a.data();
        i.call(r, s)
    })
}(jQuery),
+function(t) {
    "use strict";
    function e(e) {
        var i = e.attr("data-target");
        i || (i = e.attr("href"),
        i = i && /#[A-Za-z]/.test(i) && i.replace(/.*(?=#[^\s]*$)/, ""));
        var n = i && t(i);
        return n && n.length ? n : e.parent()
    }
    function i(i) {
        i && 3 === i.which || (t(a).remove(),
        t(r).each(function() {
            var n = t(this)
              , a = e(n)
              , r = {
                relatedTarget: this
            };
            a.hasClass("open") && (i && "click" == i.type && /input|textarea/i.test(i.target.tagName) && t.contains(a[0], i.target) || (a.trigger(i = t.Event("hide.bs.dropdown", r)),
            i.isDefaultPrevented() || (n.attr("aria-expanded", "false"),
            a.removeClass("open").trigger("hidden.bs.dropdown", r))))
        }))
    }
    function n(e) {
        return this.each(function() {
            var i = t(this)
              , n = i.data("bs.dropdown");
            n || i.data("bs.dropdown", n = new o(this)),
            "string" == typeof e && n[e].call(i)
        })
    }
    var a = ".dropdown-backdrop"
      , r = '[data-toggle="dropdown"]'
      , o = function(e) {
        t(e).on("click.bs.dropdown", this.toggle)
    };
    o.VERSION = "3.3.5",
    o.prototype.toggle = function(n) {
        var a = t(this);
        if (!a.is(".disabled, :disabled")) {
            var r = e(a)
              , o = r.hasClass("open");
            if (i(),
            !o) {
                "ontouchstart"in document.documentElement && !r.closest(".navbar-nav").length && t(document.createElement("div")).addClass("dropdown-backdrop").insertAfter(t(this)).on("click", i);
                var s = {
                    relatedTarget: this
                };
                if (r.trigger(n = t.Event("show.bs.dropdown", s)),
                n.isDefaultPrevented())
                    return;
                a.trigger("focus").attr("aria-expanded", "true"),
                r.toggleClass("open").trigger("shown.bs.dropdown", s)
            }
            return !1
        }
    }
    ,
    o.prototype.keydown = function(i) {
        if (/(38|40|27|32)/.test(i.which) && !/input|textarea/i.test(i.target.tagName)) {
            var n = t(this);
            if (i.preventDefault(),
            i.stopPropagation(),
            !n.is(".disabled, :disabled")) {
                var a = e(n)
                  , o = a.hasClass("open");
                if (!o && 27 != i.which || o && 27 == i.which)
                    return 27 == i.which && a.find(r).trigger("focus"),
                    n.trigger("click");
                var s = " li:not(.disabled):visible a"
                  , l = a.find(".dropdown-menu" + s);
                if (l.length) {
                    var c = l.index(i.target);
                    38 == i.which && c > 0 && c--,
                    40 == i.which && c < l.length - 1 && c++,
                    ~c || (c = 0),
                    l.eq(c).trigger("focus")
                }
            }
        }
    }
    ;
    var s = t.fn.dropdown;
    t.fn.dropdown = n,
    t.fn.dropdown.Constructor = o,
    t.fn.dropdown.noConflict = function() {
        return t.fn.dropdown = s,
        this
    }
    ,
    t(document).on("click.bs.dropdown.data-api", i).on("click.bs.dropdown.data-api", ".dropdown form", function(t) {
        t.stopPropagation()
    }).on("click.bs.dropdown.data-api", r, o.prototype.toggle).on("keydown.bs.dropdown.data-api", r, o.prototype.keydown).on("keydown.bs.dropdown.data-api", ".dropdown-menu", o.prototype.keydown)
}(jQuery),
+function(t) {
    "use strict";
    function e(e, n) {
        return this.each(function() {
            var a = t(this)
              , r = a.data("bs.modal")
              , o = t.extend({}, i.DEFAULTS, a.data(), "object" == typeof e && e);
            r || a.data("bs.modal", r = new i(this,o)),
            "string" == typeof e ? r[e](n) : o.show && r.show(n)
        })
    }
    var i = function(e, i) {
        this.options = i,
        this.$body = t(document.body),
        this.$element = t(e),
        this.$dialog = this.$element.find(".modal-dialog"),
        this.$backdrop = null,
        this.isShown = null,
        this.originalBodyPad = null,
        this.scrollbarWidth = 0,
        this.ignoreBackdropClick = !1,
        this.options.remote && this.$element.find(".modal-content").load(this.options.remote, t.proxy(function() {
            this.$element.trigger("loaded.bs.modal")
        }, this))
    };
    i.VERSION = "3.3.5",
    i.TRANSITION_DURATION = 300,
    i.BACKDROP_TRANSITION_DURATION = 150,
    i.DEFAULTS = {
        backdrop: !0,
        keyboard: !0,
        show: !0
    },
    i.prototype.toggle = function(t) {
        return this.isShown ? this.hide() : this.show(t)
    }
    ,
    i.prototype.show = function(e) {
        var n = this
          , a = t.Event("show.bs.modal", {
            relatedTarget: e
        });
        this.$element.trigger(a),
        this.isShown || a.isDefaultPrevented() || (this.isShown = !0,
        this.checkScrollbar(),
        this.setScrollbar(),
        this.$body.addClass("modal-open"),
        this.escape(),
        this.resize(),
        this.$element.on("click.dismiss.bs.modal", '[data-dismiss="modal"]', t.proxy(this.hide, this)),
        this.$dialog.on("mousedown.dismiss.bs.modal", function() {
            n.$element.one("mouseup.dismiss.bs.modal", function(e) {
                t(e.target).is(n.$element) && (n.ignoreBackdropClick = !0)
            })
        }),
        this.backdrop(function() {
            var a = t.support.transition && n.$element.hasClass("fade");
            n.$element.parent().length || n.$element.appendTo(n.$body),
            n.$element.show().scrollTop(0),
            n.adjustDialog(),
            a && n.$element[0].offsetWidth,
            n.$element.addClass("in"),
            n.enforceFocus();
            var r = t.Event("shown.bs.modal", {
                relatedTarget: e
            });
            a ? n.$dialog.one("bsTransitionEnd", function() {
                n.$element.trigger("focus").trigger(r)
            }).emulateTransitionEnd(i.TRANSITION_DURATION) : n.$element.trigger("focus").trigger(r)
        }))
    }
    ,
    i.prototype.hide = function(e) {
        e && e.preventDefault(),
        e = t.Event("hide.bs.modal"),
        this.$element.trigger(e),
        this.isShown && !e.isDefaultPrevented() && (this.isShown = !1,
        this.escape(),
        this.resize(),
        t(document).off("focusin.bs.modal"),
        this.$element.removeClass("in").off("click.dismiss.bs.modal").off("mouseup.dismiss.bs.modal"),
        this.$dialog.off("mousedown.dismiss.bs.modal"),
        t.support.transition && this.$element.hasClass("fade") ? this.$element.one("bsTransitionEnd", t.proxy(this.hideModal, this)).emulateTransitionEnd(i.TRANSITION_DURATION) : this.hideModal())
    }
    ,
    i.prototype.enforceFocus = function() {
        t(document).off("focusin.bs.modal").on("focusin.bs.modal", t.proxy(function(t) {
            this.$element[0] === t.target || this.$element.has(t.target).length || this.$element.trigger("focus")
        }, this))
    }
    ,
    i.prototype.escape = function() {
        this.isShown && this.options.keyboard ? this.$element.on("keydown.dismiss.bs.modal", t.proxy(function(t) {
            27 == t.which && this.hide()
        }, this)) : this.isShown || this.$element.off("keydown.dismiss.bs.modal")
    }
    ,
    i.prototype.resize = function() {
        this.isShown ? t(window).on("resize.bs.modal", t.proxy(this.handleUpdate, this)) : t(window).off("resize.bs.modal")
    }
    ,
    i.prototype.hideModal = function() {
        var e = this;
        this.$element.hide(),
        this.backdrop(function() {
            0 == t(".modal.in").length && (e.$body.removeClass("modal-open"),
            e.resetAdjustments(),
            e.resetScrollbar(),
            e.$element.trigger("hidden.bs.modal"))
        })
    }
    ,
    i.prototype.removeBackdrop = function() {
        this.$backdrop && this.$backdrop.remove(),
        this.$backdrop = null
    }
    ,
    i.prototype.backdrop = function(e) {
        var n = this
          , a = this.$element.hasClass("fade") ? "fade" : "";
        if (this.isShown && this.options.backdrop) {
            var r = t.support.transition && a;
            if (this.$backdrop = 0 == t("[rel-modal=" + this.$element.attr("id") + "]").length ? t(document.createElement("div")).addClass("modal-backdrop " + a).attr("rel-modal", this.$element.attr("id")).css("z-index", this.$element.css("z-index") - 1).appendTo(this.$body) : t("[rel-modal=" + this.$element.attr("id") + "]"),
            this.$element.on("click.dismiss.bs.modal", t.proxy(function(t) {
                return this.ignoreBackdropClick ? void (this.ignoreBackdropClick = !1) : void (t.target === t.currentTarget && ("static" == this.options.backdrop ? this.$element[0].focus() : this.hide()))
            }, this)),
            r && this.$backdrop[0].offsetWidth,
            this.$backdrop.addClass("in"),
            !e)
                return;
            r ? this.$backdrop.one("bsTransitionEnd", e).emulateTransitionEnd(i.BACKDROP_TRANSITION_DURATION) : e()
        } else if (!this.isShown && this.$backdrop) {
            this.$backdrop.removeClass("in");
            var o = function() {
                n.removeBackdrop(),
                e && e()
            };
            t.support.transition && this.$element.hasClass("fade") ? this.$backdrop.one("bsTransitionEnd", o).emulateTransitionEnd(i.BACKDROP_TRANSITION_DURATION) : o()
        } else
            e && e()
    }
    ,
    i.prototype.handleUpdate = function() {
        this.adjustDialog()
    }
    ,
    i.prototype.adjustDialog = function() {
        var t = this.$element[0].scrollHeight > document.documentElement.clientHeight;
        this.$element.css({
            paddingLeft: !this.bodyIsOverflowing && t ? this.scrollbarWidth : "",
            paddingRight: this.bodyIsOverflowing && !t ? this.scrollbarWidth : ""
        })
    }
    ,
    i.prototype.resetAdjustments = function() {
        this.$element.css({
            paddingLeft: "",
            paddingRight: ""
        })
    }
    ,
    i.prototype.checkScrollbar = function() {
        var t = window.innerWidth;
        if (!t) {
            var e = document.documentElement.getBoundingClientRect();
            t = e.right - Math.abs(e.left)
        }
        this.bodyIsOverflowing = document.body.clientWidth < t,
        this.scrollbarWidth = this.measureScrollbar()
    }
    ,
    i.prototype.setScrollbar = function() {
        var t = parseInt(this.$body.css("padding-right") || 0, 10);
        this.originalBodyPad = document.body.style.paddingRight || "",
        this.bodyIsOverflowing && this.$body.css("padding-right", t + this.scrollbarWidth)
    }
    ,
    i.prototype.resetScrollbar = function() {
        this.$body.css("padding-right", this.originalBodyPad)
    }
    ,
    i.prototype.measureScrollbar = function() {
        var t = document.createElement("div");
        t.className = "modal-scrollbar-measure",
        this.$body.append(t);
        var e = t.offsetWidth - t.clientWidth;
        return this.$body[0].removeChild(t),
        e
    }
    ;
    var n = t.fn.modal;
    t.fn.modal = e,
    t.fn.modal.Constructor = i,
    t.fn.modal.noConflict = function() {
        return t.fn.modal = n,
        this
    }
    ,
    t(document).on("click.bs.modal.data-api", '[data-toggle="modal"]', function(i) {
        var n = t(this)
          , a = n.attr("href")
          , r = t(n.attr("data-target") || a && a.replace(/.*(?=#[^\s]+$)/, ""))
          , o = r.data("bs.modal") ? "toggle" : t.extend({
            remote: !/#/.test(a) && a
        }, r.data(), n.data());
        n.is("a") && i.preventDefault(),
        r.one("show.bs.modal", function(t) {
            t.isDefaultPrevented() || r.one("hidden.bs.modal", function() {
                n.is(":visible") && n.trigger("focus")
            })
        }),
        e.call(r, o, this)
    })
}(jQuery),
+function(t) {
    "use strict";
    function e(e) {
        return this.each(function() {
            var n = t(this)
              , a = n.data("bs.tooltip")
              , r = "object" == typeof e && e;
            (a || !/destroy|hide/.test(e)) && (a || n.data("bs.tooltip", a = new i(this,r)),
            "string" == typeof e && a[e]())
        })
    }
    var i = function(t, e) {
        this.type = null,
        this.options = null,
        this.enabled = null,
        this.timeout = null,
        this.hoverState = null,
        this.$element = null,
        this.inState = null,
        this.init("tooltip", t, e)
    };
    i.VERSION = "3.3.5",
    i.TRANSITION_DURATION = 150,
    i.DEFAULTS = {
        animation: !0,
        placement: "top",
        selector: !1,
        template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
        trigger: "hover focus",
        title: "",
        delay: 0,
        html: !1,
        container: !1,
        viewport: {
            selector: "body",
            padding: 0
        }
    },
    i.prototype.init = function(e, i, n) {
        if (this.enabled = !0,
        this.type = e,
        this.$element = t(i),
        this.options = this.getOptions(n),
        this.$viewport = this.options.viewport && t(t.isFunction(this.options.viewport) ? this.options.viewport.call(this, this.$element) : this.options.viewport.selector || this.options.viewport),
        this.inState = {
            click: !1,
            hover: !1,
            focus: !1
        },
        this.$element[0]instanceof document.constructor && !this.options.selector)
            throw new Error("`selector` option must be specified when initializing " + this.type + " on the window.document object!");
        for (var a = this.options.trigger.split(" "), r = a.length; r--; ) {
            var o = a[r];
            if ("click" == o)
                this.$element.on("click." + this.type, this.options.selector, t.proxy(this.toggle, this));
            else if ("manual" != o) {
                var s = "hover" == o ? "mouseenter" : "focusin"
                  , l = "hover" == o ? "mouseleave" : "focusout";
                this.$element.on(s + "." + this.type, this.options.selector, t.proxy(this.enter, this)),
                this.$element.on(l + "." + this.type, this.options.selector, t.proxy(this.leave, this))
            }
        }
        this.options.selector ? this._options = t.extend({}, this.options, {
            trigger: "manual",
            selector: ""
        }) : this.fixTitle()
    }
    ,
    i.prototype.getDefaults = function() {
        return i.DEFAULTS
    }
    ,
    i.prototype.getOptions = function(e) {
        return e = t.extend({}, this.getDefaults(), this.$element.data(), e),
        e.delay && "number" == typeof e.delay && (e.delay = {
            show: e.delay,
            hide: e.delay
        }),
        e
    }
    ,
    i.prototype.getDelegateOptions = function() {
        var e = {}
          , i = this.getDefaults();
        return this._options && t.each(this._options, function(t, n) {
            i[t] != n && (e[t] = n)
        }),
        e
    }
    ,
    i.prototype.enter = function(e) {
        var i = e instanceof this.constructor ? e : t(e.currentTarget).data("bs." + this.type);
        return i || (i = new this.constructor(e.currentTarget,this.getDelegateOptions()),
        t(e.currentTarget).data("bs." + this.type, i)),
        e instanceof t.Event && (i.inState["focusin" == e.type ? "focus" : "hover"] = !0),
        i.tip().hasClass("in") || "in" == i.hoverState ? void (i.hoverState = "in") : (clearTimeout(i.timeout),
        i.hoverState = "in",
        i.options.delay && i.options.delay.show ? void (i.timeout = setTimeout(function() {
            "in" == i.hoverState && i.show()
        }, i.options.delay.show)) : i.show())
    }
    ,
    i.prototype.isInStateTrue = function() {
        for (var t in this.inState)
            if (this.inState[t])
                return !0;
        return !1
    }
    ,
    i.prototype.leave = function(e) {
        var i = e instanceof this.constructor ? e : t(e.currentTarget).data("bs." + this.type);
        return i || (i = new this.constructor(e.currentTarget,this.getDelegateOptions()),
        t(e.currentTarget).data("bs." + this.type, i)),
        e instanceof t.Event && (i.inState["focusout" == e.type ? "focus" : "hover"] = !1),
        i.isInStateTrue() ? void 0 : (clearTimeout(i.timeout),
        i.hoverState = "out",
        i.options.delay && i.options.delay.hide ? void (i.timeout = setTimeout(function() {
            "out" == i.hoverState && i.hide()
        }, i.options.delay.hide)) : i.hide())
    }
    ,
    i.prototype.show = function() {
        var e = t.Event("show.bs." + this.type);
        if (this.hasContent() && this.enabled) {
            this.$element.trigger(e);
            var n = t.contains(this.$element[0].ownerDocument.documentElement, this.$element[0]);
            if (e.isDefaultPrevented() || !n)
                return;
            var a = this
              , r = this.tip()
              , o = this.getUID(this.type);
            this.setContent(),
            r.attr("id", o),
            this.$element.attr("aria-describedby", o),
            this.options.animation && r.addClass("fade");
            var s = "function" == typeof this.options.placement ? this.options.placement.call(this, r[0], this.$element[0]) : this.options.placement
              , l = /\s?auto?\s?/i
              , c = l.test(s);
            c && (s = s.replace(l, "") || "top"),
            r.detach().css({
                top: 0,
                left: 0,
                display: "block"
            }).addClass(s).data("bs." + this.type, this),
            this.options.container ? r.appendTo(this.options.container) : r.insertAfter(this.$element),
            this.$element.trigger("inserted.bs." + this.type);
            var u = this.getPosition()
              , d = r[0].offsetWidth
              , h = r[0].offsetHeight;
            if (c) {
                var p = s
                  , f = this.getPosition(this.$viewport);
                s = "bottom" == s && u.bottom + h > f.bottom ? "top" : "top" == s && u.top - h < f.top ? "bottom" : "right" == s && u.right + d > f.width ? "left" : "left" == s && u.left - d < f.left ? "right" : s,
                r.removeClass(p).addClass(s)
            }
            var m = this.getCalculatedOffset(s, u, d, h);
            this.applyPlacement(m, s);
            var g = function() {
                var t = a.hoverState;
                a.$element.trigger("shown.bs." + a.type),
                a.hoverState = null,
                "out" == t && a.leave(a)
            };
            t.support.transition && this.$tip.hasClass("fade") ? r.one("bsTransitionEnd", g).emulateTransitionEnd(i.TRANSITION_DURATION) : g()
        }
    }
    ,
    i.prototype.applyPlacement = function(e, i) {
        var n = this.tip()
          , a = n[0].offsetWidth
          , r = n[0].offsetHeight
          , o = parseInt(n.css("margin-top"), 10)
          , s = parseInt(n.css("margin-left"), 10);
        isNaN(o) && (o = 0),
        isNaN(s) && (s = 0),
        e.top += o,
        e.left += s,
        t.offset.setOffset(n[0], t.extend({
            using: function(t) {
                n.css({
                    top: Math.round(t.top),
                    left: Math.round(t.left)
                })
            }
        }, e), 0),
        n.addClass("in");
        var l = n[0].offsetWidth
          , c = n[0].offsetHeight;
        "top" == i && c != r && (e.top = e.top + r - c);
        var u = this.getViewportAdjustedDelta(i, e, l, c);
        u.left ? e.left += u.left : e.top += u.top;
        var d = /top|bottom/.test(i)
          , h = d ? 2 * u.left - a + l : 2 * u.top - r + c
          , p = d ? "offsetWidth" : "offsetHeight";
        n.offset(e),
        this.replaceArrow(h, n[0][p], d)
    }
    ,
    i.prototype.replaceArrow = function(t, e, i) {
        this.arrow().css(i ? "left" : "top", 50 * (1 - t / e) + "%").css(i ? "top" : "left", "")
    }
    ,
    i.prototype.setContent = function() {
        var t = this.tip()
          , e = this.getTitle();
        t.find(".tooltip-inner")[this.options.html ? "html" : "text"](e),
        t.removeClass("fade in top bottom left right")
    }
    ,
    i.prototype.hide = function(e) {
        function n() {
            "in" != a.hoverState && r.detach(),
            a.$element.removeAttr("aria-describedby").trigger("hidden.bs." + a.type),
            e && e()
        }
        var a = this
          , r = t(this.$tip)
          , o = t.Event("hide.bs." + this.type);
        return this.$element.trigger(o),
        o.isDefaultPrevented() ? void 0 : (r.removeClass("in"),
        t.support.transition && r.hasClass("fade") ? r.one("bsTransitionEnd", n).emulateTransitionEnd(i.TRANSITION_DURATION) : n(),
        this.hoverState = null,
        this)
    }
    ,
    i.prototype.fixTitle = function() {
        var t = this.$element;
        (t.attr("title") || "string" != typeof t.attr("data-original-title")) && t.attr("data-original-title", t.attr("title") || "").attr("title", "")
    }
    ,
    i.prototype.hasContent = function() {
        return this.getTitle()
    }
    ,
    i.prototype.getPosition = function(e) {
        e = e || this.$element;
        var i = e[0]
          , n = "BODY" == i.tagName
          , a = i.getBoundingClientRect();
        null == a.width && (a = t.extend({}, a, {
            width: a.right - a.left,
            height: a.bottom - a.top
        }));
        var r = n ? {
            top: 0,
            left: 0
        } : e.offset()
          , o = {
            scroll: n ? document.documentElement.scrollTop || document.body.scrollTop : e.scrollTop()
        }
          , s = n ? {
            width: t(window).width(),
            height: t(window).height()
        } : null;
        return t.extend({}, a, o, s, r)
    }
    ,
    i.prototype.getCalculatedOffset = function(t, e, i, n) {
        return "bottom" == t ? {
            top: e.top + e.height,
            left: e.left + e.width / 2 - i / 2
        } : "top" == t ? {
            top: e.top - n,
            left: e.left + e.width / 2 - i / 2
        } : "left" == t ? {
            top: e.top + e.height / 2 - n / 2,
            left: e.left - i
        } : {
            top: e.top + e.height / 2 - n / 2,
            left: e.left + e.width
        }
    }
    ,
    i.prototype.getViewportAdjustedDelta = function(t, e, i, n) {
        var a = {
            top: 0,
            left: 0
        };
        if (!this.$viewport)
            return a;
        var r = this.options.viewport && this.options.viewport.padding || 0
          , o = this.getPosition(this.$viewport);
        if (/right|left/.test(t)) {
            var s = e.top - r - o.scroll
              , l = e.top + r - o.scroll + n;
            s < o.top ? a.top = o.top - s : l > o.top + o.height && (a.top = o.top + o.height - l)
        } else {
            var c = e.left - r
              , u = e.left + r + i;
            c < o.left ? a.left = o.left - c : u > o.right && (a.left = o.left + o.width - u)
        }
        return a
    }
    ,
    i.prototype.getTitle = function() {
        var t, e = this.$element, i = this.options;
        return t = e.attr("data-original-title") || ("function" == typeof i.title ? i.title.call(e[0]) : i.title)
    }
    ,
    i.prototype.getUID = function(t) {
        do
            t += ~~(1e6 * Math.random());
        while (document.getElementById(t));return t
    }
    ,
    i.prototype.tip = function() {
        if (!this.$tip && (this.$tip = t(this.options.template),
        1 != this.$tip.length))
            throw new Error(this.type + " `template` option must consist of exactly 1 top-level element!");
        return this.$tip
    }
    ,
    i.prototype.arrow = function() {
        return this.$arrow = this.$arrow || this.tip().find(".tooltip-arrow")
    }
    ,
    i.prototype.enable = function() {
        this.enabled = !0
    }
    ,
    i.prototype.disable = function() {
        this.enabled = !1
    }
    ,
    i.prototype.toggleEnabled = function() {
        this.enabled = !this.enabled
    }
    ,
    i.prototype.toggle = function(e) {
        var i = this;
        e && (i = t(e.currentTarget).data("bs." + this.type),
        i || (i = new this.constructor(e.currentTarget,this.getDelegateOptions()),
        t(e.currentTarget).data("bs." + this.type, i))),
        e ? (i.inState.click = !i.inState.click,
        i.isInStateTrue() ? i.enter(i) : i.leave(i)) : i.tip().hasClass("in") ? i.leave(i) : i.enter(i)
    }
    ,
    i.prototype.destroy = function() {
        var t = this;
        clearTimeout(this.timeout),
        this.hide(function() {
            t.$element.off("." + t.type).removeData("bs." + t.type),
            t.$tip && t.$tip.detach(),
            t.$tip = null,
            t.$arrow = null,
            t.$viewport = null
        })
    }
    ;
    var n = t.fn.tooltip;
    t.fn.tooltip = e,
    t.fn.tooltip.Constructor = i,
    t.fn.tooltip.noConflict = function() {
        return t.fn.tooltip = n,
        this
    }
}(jQuery),
+function(t) {
    "use strict";
    function e(e) {
        return this.each(function() {
            var n = t(this)
              , a = n.data("bs.popover")
              , r = "object" == typeof e && e;
            (a || !/destroy|hide/.test(e)) && (a || n.data("bs.popover", a = new i(this,r)),
            "string" == typeof e && a[e]())
        })
    }
    var i = function(t, e) {
        this.init("popover", t, e)
    };
    if (!t.fn.tooltip)
        throw new Error("Popover requires tooltip.js");
    i.VERSION = "3.3.5",
    i.DEFAULTS = t.extend({}, t.fn.tooltip.Constructor.DEFAULTS, {
        placement: "right",
        trigger: "click",
        content: "",
        template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
    }),
    i.prototype = t.extend({}, t.fn.tooltip.Constructor.prototype),
    i.prototype.constructor = i,
    i.prototype.getDefaults = function() {
        return i.DEFAULTS
    }
    ,
    i.prototype.setContent = function() {
        var t = this.tip()
          , e = this.getTitle()
          , i = this.getContent();
        t.find(".popover-title")[this.options.html ? "html" : "text"](e),
        t.find(".popover-content").children().detach().end()[this.options.html ? "string" == typeof i ? "html" : "append" : "text"](i),
        t.removeClass("fade top bottom left right in"),
        t.find(".popover-title").html() || t.find(".popover-title").hide()
    }
    ,
    i.prototype.hasContent = function() {
        return this.getTitle() || this.getContent()
    }
    ,
    i.prototype.getContent = function() {
        var t = this.$element
          , e = this.options;
        return t.attr("data-content") || ("function" == typeof e.content ? e.content.call(t[0]) : e.content)
    }
    ,
    i.prototype.arrow = function() {
        return this.$arrow = this.$arrow || this.tip().find(".arrow")
    }
    ;
    var n = t.fn.popover;
    t.fn.popover = e,
    t.fn.popover.Constructor = i,
    t.fn.popover.noConflict = function() {
        return t.fn.popover = n,
        this
    }
}(jQuery),
+function(t) {
    "use strict";
    function e(i, n) {
        this.$body = t(document.body),
        this.$scrollElement = t(t(i).is(document.body) ? window : i),
        this.options = t.extend({}, e.DEFAULTS, n),
        this.selector = (this.options.target || "") + " .nav li > a",
        this.offsets = [],
        this.targets = [],
        this.activeTarget = null,
        this.scrollHeight = 0,
        this.$scrollElement.on("scroll.bs.scrollspy", t.proxy(this.process, this)),
        this.refresh(),
        this.process()
    }
    function i(i) {
        return this.each(function() {
            var n = t(this)
              , a = n.data("bs.scrollspy")
              , r = "object" == typeof i && i;
            a || n.data("bs.scrollspy", a = new e(this,r)),
            "string" == typeof i && a[i]()
        })
    }
    e.VERSION = "3.3.5",
    e.DEFAULTS = {
        offset: 10
    },
    e.prototype.getScrollHeight = function() {
        return this.$scrollElement[0].scrollHeight || Math.max(this.$body[0].scrollHeight, document.documentElement.scrollHeight)
    }
    ,
    e.prototype.refresh = function() {
        var e = this
          , i = "offset"
          , n = 0;
        this.offsets = [],
        this.targets = [],
        this.scrollHeight = this.getScrollHeight(),
        t.isWindow(this.$scrollElement[0]) || (i = "position",
        n = this.$scrollElement.scrollTop()),
        this.$body.find(this.selector).map(function() {
            var e = t(this)
              , a = e.data("target") || e.attr("href")
              , r = /^#./.test(a) && t(a);
            return r && r.length && r.is(":visible") && [[r[i]().top + n, a]] || null
        }).sort(function(t, e) {
            return t[0] - e[0]
        }).each(function() {
            e.offsets.push(this[0]),
            e.targets.push(this[1])
        })
    }
    ,
    e.prototype.process = function() {
        var t, e = this.$scrollElement.scrollTop() + this.options.offset, i = this.getScrollHeight(), n = this.options.offset + i - this.$scrollElement.height(), a = this.offsets, r = this.targets, o = this.activeTarget;
        if (this.scrollHeight != i && this.refresh(),
        e >= n)
            return o != (t = r[r.length - 1]) && this.activate(t);
        if (o && e < a[0])
            return this.activeTarget = null,
            this.clear();
        for (t = a.length; t--; )
            o != r[t] && e >= a[t] && (void 0 === a[t + 1] || e < a[t + 1]) && this.activate(r[t])
    }
    ,
    e.prototype.activate = function(e) {
        this.activeTarget = e,
        this.clear();
        var i = this.selector + '[data-target="' + e + '"],' + this.selector + '[href="' + e + '"]'
          , n = t(i).parents("li").addClass("active");
        n.parent(".dropdown-menu").length && (n = n.closest("li.dropdown").addClass("active")),
        n.trigger("activate.bs.scrollspy")
    }
    ,
    e.prototype.clear = function() {
        t(this.selector).parentsUntil(this.options.target, ".active").removeClass("active")
    }
    ;
    var n = t.fn.scrollspy;
    t.fn.scrollspy = i,
    t.fn.scrollspy.Constructor = e,
    t.fn.scrollspy.noConflict = function() {
        return t.fn.scrollspy = n,
        this
    }
    ,
    t(window).on("load.bs.scrollspy.data-api", function() {
        t('[data-spy="scroll"]').each(function() {
            var e = t(this);
            i.call(e, e.data())
        })
    })
}(jQuery),
+function(t) {
    "use strict";
    function e(e) {
        return this.each(function() {
            var n = t(this)
              , a = n.data("bs.tab");
            a || n.data("bs.tab", a = new i(this)),
            "string" == typeof e && a[e]()
        })
    }
    var i = function(e) {
        this.element = t(e)
    };
    i.VERSION = "3.3.5",
    i.TRANSITION_DURATION = 150,
    i.prototype.show = function() {
        var e = this.element
          , i = e.closest("ul:not(.dropdown-menu)")
          , n = e.data("target");
        if (n || (n = e.attr("href"),
        n = n && n.replace(/.*(?=#[^\s]*$)/, "")),
        !e.parent("li").hasClass("active")) {
            var a = i.find(".active:last a")
              , r = t.Event("hide.bs.tab", {
                relatedTarget: e[0]
            })
              , o = t.Event("show.bs.tab", {
                relatedTarget: a[0]
            });
            if (a.trigger(r),
            e.trigger(o),
            !o.isDefaultPrevented() && !r.isDefaultPrevented()) {
                var s = t(n);
                this.activate(e.closest("li"), i),
                this.activate(s, s.parent(), function() {
                    a.trigger({
                        type: "hidden.bs.tab",
                        relatedTarget: e[0]
                    }),
                    e.trigger({
                        type: "shown.bs.tab",
                        relatedTarget: a[0]
                    })
                })
            }
        }
    }
    ,
    i.prototype.activate = function(e, n, a) {
        function r() {
            o.removeClass("active").find("> .dropdown-menu > .active").removeClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded", !1),
            e.addClass("active").find('[data-toggle="tab"]').attr("aria-expanded", !0),
            s ? (e[0].offsetWidth,
            e.addClass("in")) : e.removeClass("fade"),
            e.parent(".dropdown-menu").length && e.closest("li.dropdown").addClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded", !0),
            a && a()
        }
        var o = n.find("> .active")
          , s = a && t.support.transition && (o.length && o.hasClass("fade") || !!n.find("> .fade").length);
        o.length && s ? o.one("bsTransitionEnd", r).emulateTransitionEnd(i.TRANSITION_DURATION) : r(),
        o.removeClass("in")
    }
    ;
    var n = t.fn.tab;
    t.fn.tab = e,
    t.fn.tab.Constructor = i,
    t.fn.tab.noConflict = function() {
        return t.fn.tab = n,
        this
    }
    ;
    var a = function(i) {
        i.preventDefault(),
        e.call(t(this), "show")
    };
    t(document).on("click.bs.tab.data-api", '[data-toggle="tab"]', a).on("click.bs.tab.data-api", '[data-toggle="pill"]', a)
}(jQuery),
+function(t) {
    "use strict";
    function e(e) {
        return this.each(function() {
            var n = t(this)
              , a = n.data("bs.affix")
              , r = "object" == typeof e && e;
            a || n.data("bs.affix", a = new i(this,r)),
            "string" == typeof e && a[e]()
        })
    }
    var i = function(e, n) {
        this.options = t.extend({}, i.DEFAULTS, n),
        this.$target = t(this.options.target).on("scroll.bs.affix.data-api", t.proxy(this.checkPosition, this)).on("click.bs.affix.data-api", t.proxy(this.checkPositionWithEventLoop, this)),
        this.$element = t(e),
        this.affixed = null,
        this.unpin = null,
        this.pinnedOffset = null,
        this.checkPosition()
    };
    i.VERSION = "3.3.5",
    i.RESET = "affix affix-top affix-bottom",
    i.DEFAULTS = {
        offset: 0,
        target: window
    },
    i.prototype.getState = function(t, e, i, n) {
        var a = this.$target.scrollTop()
          , r = this.$element.offset()
          , o = this.$target.height();
        if (null != i && "top" == this.affixed)
            return i > a ? "top" : !1;
        if ("bottom" == this.affixed)
            return null != i ? a + this.unpin <= r.top ? !1 : "bottom" : t - n >= a + o ? !1 : "bottom";
        var s = null == this.affixed
          , l = s ? a : r.top
          , c = s ? o : e;
        return null != i && i >= a ? "top" : null != n && l + c >= t - n ? "bottom" : !1
    }
    ,
    i.prototype.getPinnedOffset = function() {
        if (this.pinnedOffset)
            return this.pinnedOffset;
        this.$element.removeClass(i.RESET).addClass("affix");
        var t = this.$target.scrollTop()
          , e = this.$element.offset();
        return this.pinnedOffset = e.top - t
    }
    ,
    i.prototype.checkPositionWithEventLoop = function() {
        setTimeout(t.proxy(this.checkPosition, this), 1)
    }
    ,
    i.prototype.checkPosition = function() {
        if (this.$element.is(":visible")) {
            var e = this.$element.height()
              , n = this.options.offset
              , a = n.top
              , r = n.bottom
              , o = Math.max(t(document).height(), t(document.body).height());
            "object" != typeof n && (r = a = n),
            "function" == typeof a && (a = n.top(this.$element)),
            "function" == typeof r && (r = n.bottom(this.$element));
            var s = this.getState(o, e, a, r);
            if (this.affixed != s) {
                null != this.unpin && this.$element.css("top", "");
                var l = "affix" + (s ? "-" + s : "")
                  , c = t.Event(l + ".bs.affix");
                if (this.$element.trigger(c),
                c.isDefaultPrevented())
                    return;
                this.affixed = s,
                this.unpin = "bottom" == s ? this.getPinnedOffset() : null,
                this.$element.removeClass(i.RESET).addClass(l).trigger(l.replace("affix", "affixed") + ".bs.affix")
            }
            "bottom" == s && this.$element.offset({
                top: o - e - r
            })
        }
    }
    ;
    var n = t.fn.affix;
    t.fn.affix = e,
    t.fn.affix.Constructor = i,
    t.fn.affix.noConflict = function() {
        return t.fn.affix = n,
        this
    }
    ,
    t(window).on("load", function() {
        t('[data-spy="affix"]').each(function() {
            var i = t(this)
              , n = i.data();
            n.offset = n.offset || {},
            null != n.offsetBottom && (n.offset.bottom = n.offsetBottom),
            null != n.offsetTop && (n.offset.top = n.offsetTop),
            e.call(i, n)
        })
    })
}(jQuery),
function(t, e) {
    "function" == typeof define && define.amd ? define(["jquery"], function(t) {
        return e(t)
    }) : "object" == typeof exports ? module.exports = e(require("jquery")) : e(jQuery)
}(this, function() {
    !function(t) {
        "use strict";
        function e(e) {
            var i = [{
                re: /[\xC0-\xC6]/g,
                ch: "A"
            }, {
                re: /[\xE0-\xE6]/g,
                ch: "a"
            }, {
                re: /[\xC8-\xCB]/g,
                ch: "E"
            }, {
                re: /[\xE8-\xEB]/g,
                ch: "e"
            }, {
                re: /[\xCC-\xCF]/g,
                ch: "I"
            }, {
                re: /[\xEC-\xEF]/g,
                ch: "i"
            }, {
                re: /[\xD2-\xD6]/g,
                ch: "O"
            }, {
                re: /[\xF2-\xF6]/g,
                ch: "o"
            }, {
                re: /[\xD9-\xDC]/g,
                ch: "U"
            }, {
                re: /[\xF9-\xFC]/g,
                ch: "u"
            }, {
                re: /[\xC7-\xE7]/g,
                ch: "c"
            }, {
                re: /[\xD1]/g,
                ch: "N"
            }, {
                re: /[\xF1]/g,
                ch: "n"
            }];
            return t.each(i, function() {
                e = e.replace(this.re, this.ch)
            }),
            e
        }
        function i(t) {
            var e = {
                "&": "&amp;",
                "<": "&lt;",
                ">": "&gt;",
                '"': "&quot;",
                "'": "&#x27;",
                "`": "&#x60;"
            }
              , i = "(?:" + Object.keys(e).join("|") + ")"
              , n = new RegExp(i)
              , a = new RegExp(i,"g")
              , r = null == t ? "" : "" + t;
            return n.test(r) ? r.replace(a, function(t) {
                return e[t]
            }) : r
        }
        function n(e, i) {
            var n = arguments
              , r = e
              , o = i;
            [].shift.apply(n);
            var s, l = this.each(function() {
                var e = t(this);
                if (e.is("select")) {
                    var i = e.data("selectpicker")
                      , l = "object" == typeof r && r;
                    if (i) {
                        if (l)
                            for (var c in l)
                                l.hasOwnProperty(c) && (i.options[c] = l[c])
                    } else {
                        var u = t.extend({}, a.DEFAULTS, t.fn.selectpicker.defaults || {}, e.data(), l);
                        e.data("selectpicker", i = new a(this,u,o))
                    }
                    "string" == typeof r && (s = i[r]instanceof Function ? i[r].apply(i, n) : i.options[r])
                }
            });
            return "undefined" != typeof s ? s : l
        }
        String.prototype.includes || !function() {
            var t = {}.toString
              , e = function() {
                try {
                    var t = {}
                      , e = Object.defineProperty
                      , i = e(t, t, t) && e
                } catch (n) {}
                return i
            }()
              , i = "".indexOf
              , n = function(e) {
                if (null == this)
                    throw TypeError();
                var n = String(this);
                if (e && "[object RegExp]" == t.call(e))
                    throw TypeError();
                var a = n.length
                  , r = String(e)
                  , o = r.length
                  , s = arguments.length > 1 ? arguments[1] : void 0
                  , l = s ? Number(s) : 0;
                l != l && (l = 0);
                var c = Math.min(Math.max(l, 0), a);
                return o + c > a ? !1 : -1 != i.call(n, r, l)
            };
            e ? e(String.prototype, "includes", {
                value: n,
                configurable: !0,
                writable: !0
            }) : String.prototype.includes = n
        }(),
        String.prototype.startsWith || !function() {
            var t = function() {
                try {
                    var t = {}
                      , e = Object.defineProperty
                      , i = e(t, t, t) && e
                } catch (n) {}
                return i
            }()
              , e = {}.toString
              , i = function(t) {
                if (null == this)
                    throw TypeError();
                var i = String(this);
                if (t && "[object RegExp]" == e.call(t))
                    throw TypeError();
                var n = i.length
                  , a = String(t)
                  , r = a.length
                  , o = arguments.length > 1 ? arguments[1] : void 0
                  , s = o ? Number(o) : 0;
                s != s && (s = 0);
                var l = Math.min(Math.max(s, 0), n);
                if (r + l > n)
                    return !1;
                for (var c = -1; ++c < r; )
                    if (i.charCodeAt(l + c) != a.charCodeAt(c))
                        return !1;
                return !0
            };
            t ? t(String.prototype, "startsWith", {
                value: i,
                configurable: !0,
                writable: !0
            }) : String.prototype.startsWith = i
        }(),
        Object.keys || (Object.keys = function(t, e, i) {
            i = [];
            for (e in t)
                i.hasOwnProperty.call(t, e) && i.push(e);
            return i
        }
        ),
        t.expr[":"].icontains = function(e, i, n) {
            var a = t(e)
              , r = (a.data("tokens") || a.text()).toUpperCase();
            return r.includes(n[3].toUpperCase())
        }
        ,
        t.expr[":"].ibegins = function(e, i, n) {
            var a = t(e)
              , r = (a.data("tokens") || a.text()).toUpperCase();
            return r.startsWith(n[3].toUpperCase())
        }
        ,
        t.expr[":"].aicontains = function(e, i, n) {
            var a = t(e)
              , r = (a.data("tokens") || a.data("normalizedText") || a.text()).toUpperCase();
            return r.includes(n[3].toUpperCase())
        }
        ,
        t.expr[":"].aibegins = function(e, i, n) {
            var a = t(e)
              , r = (a.data("tokens") || a.data("normalizedText") || a.text()).toUpperCase();
            return r.startsWith(n[3].toUpperCase())
        }
        ;
        var a = function(e, i, n) {
            n && (n.stopPropagation(),
            n.preventDefault()),
            this.$element = t(e),
            this.$newElement = null,
            this.$button = null,
            this.$menu = null,
            this.$lis = null,
            this.options = i,
            null === this.options.title && (this.options.title = this.$element.attr("title")),
            this.val = a.prototype.val,
            this.render = a.prototype.render,
            this.refresh = a.prototype.refresh,
            this.setStyle = a.prototype.setStyle,
            this.selectAll = a.prototype.selectAll,
            this.deselectAll = a.prototype.deselectAll,
            this.destroy = a.prototype.remove,
            this.remove = a.prototype.remove,
            this.show = a.prototype.show,
            this.hide = a.prototype.hide,
            this.init()
        };
        a.VERSION = "1.7.2",
        a.DEFAULTS = {
            noneSelectedText: "Nothing selected",
            noneResultsText: "No results matched {0}",
            countSelectedText: function(t) {
                return 1 == t ? "{0} item selected" : "{0} items selected"
            },
            maxOptionsText: function(t, e) {
                return [1 == t ? "Limit reached ({n} item max)" : "Limit reached ({n} items max)", 1 == e ? "Group limit reached ({n} item max)" : "Group limit reached ({n} items max)"]
            },
            selectAllText: "Select All",
            deselectAllText: "Deselect All",
            doneButton: !1,
            doneButtonText: "Close",
            multipleSeparator: ", ",
            styleBase: "btn",
            style: "btn-default",
            size: "auto",
            title: null,
            selectedTextFormat: "values",
            width: !1,
            container: !1,
            hideDisabled: !1,
            showSubtext: !1,
            showIcon: !0,
            showContent: !0,
            dropupAuto: !0,
            header: !1,
            liveSearch: !1,
            liveSearchPlaceholder: null,
            liveSearchNormalize: !1,
            liveSearchStyle: "contains",
            actionsBox: !1,
            iconBase: "glyphicon",
            tickIcon: "glyphicon-ok",
            maxOptions: !1,
            mobile: !1,
            selectOnTab: !1,
            dropdownAlignRight: !1
        },
        a.prototype = {
            constructor: a,
            init: function() {
                var e = this
                  , i = this.$element.attr("id");
                this.$element.addClass("bs-select-hidden"),
                this.liObj = {},
                this.multiple = this.$element.prop("multiple"),
                this.autofocus = this.$element.prop("autofocus"),
                this.$newElement = this.createView(),
                this.$element.after(this.$newElement),
                this.$button = this.$newElement.children("button"),
                this.$menu = this.$newElement.children(".dropdown-menu"),
                this.$menuInner = this.$menu.children(".inner"),
                this.$searchbox = this.$menu.find("input"),
                this.options.dropdownAlignRight && this.$menu.addClass("dropdown-menu-right"),
                "undefined" != typeof i && (this.$button.attr("data-id", i),
                t('label[for="' + i + '"]').click(function(t) {
                    t.preventDefault(),
                    e.$button.focus()
                })),
                this.checkDisabled(),
                this.clickListener(),
                this.options.liveSearch && this.liveSearchListener(),
                this.render(),
                this.setStyle(),
                this.setWidth(),
                this.options.container && this.selectPosition(),
                this.$menu.data("this", this),
                this.$newElement.data("this", this),
                this.options.mobile && this.mobile(),
                this.$newElement.on("hide.bs.dropdown", function(t) {
                    e.$element.trigger("hide.bs.select", t)
                }),
                this.$newElement.on("hidden.bs.dropdown", function(t) {
                    e.$element.trigger("hidden.bs.select", t)
                }),
                this.$newElement.on("show.bs.dropdown", function(t) {
                    e.$element.trigger("show.bs.select", t)
                }),
                this.$newElement.on("shown.bs.dropdown", function(t) {
                    e.$element.trigger("shown.bs.select", t)
                }),
                setTimeout(function() {
                    e.$element.trigger("loaded.bs.select")
                })
            },
            createDropdown: function() {
                var e = this.multiple ? " show-tick" : ""
                  , n = this.$element.parent().hasClass("input-group") ? " input-group-btn" : ""
                  , a = this.autofocus ? " autofocus" : ""
                  , r = this.options.header ? '<div class="popover-title"><button type="button" class="close" aria-hidden="true">&times;</button>' + this.options.header + "</div>" : ""
                  , o = this.options.liveSearch ? '<div class="bs-searchbox"><input type="text" class="form-control" autocomplete="off"' + (null === this.options.liveSearchPlaceholder ? "" : ' placeholder="' + i(this.options.liveSearchPlaceholder) + '"') + "></div>" : ""
                  , s = this.multiple && this.options.actionsBox ? '<div class="bs-actionsbox"><div class="btn-group btn-group-sm btn-block"><button type="button" class="actions-btn bs-select-all btn btn-default">' + this.options.selectAllText + '</button><button type="button" class="actions-btn bs-deselect-all btn btn-default">' + this.options.deselectAllText + "</button></div></div>" : ""
                  , l = this.multiple && this.options.doneButton ? '<div class="bs-donebutton"><div class="btn-group btn-block"><button type="button" class="btn btn-sm btn-default">' + this.options.doneButtonText + "</button></div></div>" : ""
                  , c = '<div class="btn-group bootstrap-select' + e + n + '"><button type="button" class="' + this.options.styleBase + ' dropdown-toggle" data-toggle="dropdown"' + a + '><span class="filter-option pull-left"></span>&nbsp;<span class="caret"></span></button><div class="dropdown-menu open">' + r + o + s + '<ul class="dropdown-menu inner" role="menu"></ul>' + l + "</div></div>";
                return t(c)
            },
            createView: function() {
                var t = this.createDropdown()
                  , e = this.createLi();
                return t.find("ul")[0].innerHTML = e,
                t
            },
            reloadLi: function() {
                this.destroyLi();
                var t = this.createLi();
                this.$menuInner[0].innerHTML = t
            },
            destroyLi: function() {
                this.$menu.find("li").remove()
            },
            createLi: function() {
                var n = this
                  , a = []
                  , r = 0
                  , o = document.createElement("option")
                  , s = -1
                  , l = function(t, e, i, n) {
                    return "<li" + ("undefined" != typeof i & "" !== i ? ' class="' + i + '"' : "") + ("undefined" != typeof e & null !== e ? ' data-original-index="' + e + '"' : "") + ("undefined" != typeof n & null !== n ? 'data-optgroup="' + n + '"' : "") + ">" + t + "</li>"
                }
                  , c = function(t, a, r, o) {
                    return '<a tabindex="0"' + ("undefined" != typeof a ? ' class="' + a + '"' : "") + ("undefined" != typeof r ? ' style="' + r + '"' : "") + (n.options.liveSearchNormalize ? ' data-normalized-text="' + e(i(t)) + '"' : "") + ("undefined" != typeof o || null !== o ? ' data-tokens="' + o + '"' : "") + ">" + t + '<span class="' + n.options.iconBase + " " + n.options.tickIcon + ' check-mark"></span></a>'
                };
                if (this.options.title && !this.multiple && (s--,
                !this.$element.find(".bs-title-option").length)) {
                    var u = this.$element[0];
                    o.className = "bs-title-option",
                    o.appendChild(document.createTextNode(this.options.title)),
                    o.value = "",
                    u.insertBefore(o, u.firstChild),
                    null === u.options[u.selectedIndex].getAttribute("selected") && (o.selected = !0)
                }
                return this.$element.find("option").each(function(e) {
                    var i = t(this);
                    if (s++,
                    !i.hasClass("bs-title-option")) {
                        var o = this.className || ""
                          , u = this.style.cssText
                          , d = i.data("content") ? i.data("content") : i.html()
                          , h = i.data("tokens") ? i.data("tokens") : null
                          , p = "undefined" != typeof i.data("subtext") ? '<small class="text-muted">' + i.data("subtext") + "</small>" : ""
                          , f = "undefined" != typeof i.data("icon") ? '<span class="' + n.options.iconBase + " " + i.data("icon") + '"></span> ' : ""
                          , m = this.disabled || "OPTGROUP" === this.parentElement.tagName && this.parentElement.disabled;
                        if ("" !== f && m && (f = "<span>" + f + "</span>"),
                        n.options.hideDisabled && m)
                            return void s--;
                        if (i.data("content") || (d = f + '<span class="text">' + d + p + "</span>"),
                        "OPTGROUP" === this.parentElement.tagName && i.data("divider") !== !0) {
                            if (0 === i.index()) {
                                r += 1;
                                var g = this.parentElement.label
                                  , v = "undefined" != typeof i.parent().data("subtext") ? '<small class="text-muted">' + i.parent().data("subtext") + "</small>" : ""
                                  , _ = i.parent().data("icon") ? '<span class="' + n.options.iconBase + " " + i.parent().data("icon") + '"></span> ' : ""
                                  , y = " " + this.parentElement.className || "";
                                g = _ + '<span class="text">' + g + v + "</span>",
                                0 !== e && a.length > 0 && (s++,
                                a.push(l("", null, "divider", r + "div"))),
                                s++,
                                a.push(l(g, null, "dropdown-header" + y, r))
                            }
                            a.push(l(c(d, "opt " + o + y, u, h), e, "", r))
                        } else
                            i.data("divider") === !0 ? a.push(l("", e, "divider")) : i.data("hidden") === !0 ? a.push(l(c(d, o, u, h), e, "hidden is-hidden")) : (this.previousElementSibling && "OPTGROUP" === this.previousElementSibling.tagName && (s++,
                            a.push(l("", null, "divider", r + "div"))),
                            a.push(l(c(d, o, u, h), e)));
                        n.liObj[e] = s
                    }
                }),
                this.multiple || 0 !== this.$element.find("option:selected").length || this.options.title || this.$element.find("option").eq(0).prop("selected", !0).attr("selected", "selected"),
                a.join("")
            },
            findLis: function() {
                return null == this.$lis && (this.$lis = this.$menu.find("li")),
                this.$lis
            },
            render: function(e) {
                var i, n = this;
                e !== !1 && this.$element.find("option").each(function(t) {
                    var e = n.findLis().eq(n.liObj[t]);
                    n.setDisabled(t, this.disabled || "OPTGROUP" === this.parentElement.tagName && this.parentElement.disabled, e),
                    n.setSelected(t, this.selected, e)
                }),
                this.tabIndex();
                var a = this.$element.find("option").map(function() {
                    if (this.selected) {
                        if (n.options.hideDisabled && (this.disabled || "OPTGROUP" === this.parentElement.tagName && this.parentElement.disabled))
                            return !1;
                        var e, i = t(this), a = i.data("icon") && n.options.showIcon ? '<i class="' + n.options.iconBase + " " + i.data("icon") + '"></i> ' : "";
                        return e = n.options.showSubtext && i.data("subtext") && !n.multiple ? ' <small class="text-muted">' + i.data("subtext") + "</small>" : "",
                        "undefined" != typeof i.attr("title") ? i.attr("title") : i.data("content") && n.options.showContent ? i.data("content") : a + i.html() + e
                    }
                }).toArray()
                  , r = this.multiple ? a.join(this.options.multipleSeparator) : a[0];
                if (this.multiple && this.options.selectedTextFormat.indexOf("count") > -1) {
                    var o = this.options.selectedTextFormat.split(">");
                    if (o.length > 1 && a.length > o[1] || 1 == o.length && a.length >= 2) {
                        i = this.options.hideDisabled ? ", [disabled]" : "";
                        var s = this.$element.find("option").not('[data-divider="true"], [data-hidden="true"]' + i).length
                          , l = "function" == typeof this.options.countSelectedText ? this.options.countSelectedText(a.length, s) : this.options.countSelectedText;
                        r = l.replace("{0}", a.length.toString()).replace("{1}", s.toString())
                    }
                }
                void 0 == this.options.title && (this.options.title = this.$element.attr("title")),
                "static" == this.options.selectedTextFormat && (r = this.options.title),
                r || (r = "undefined" != typeof this.options.title ? this.options.title : this.options.noneSelectedText),
                this.$button.attr("title", t.trim(r.replace(/<[^>]*>?/g, ""))),
                this.$button.children(".filter-option").html(r),
                this.$element.trigger("rendered.bs.select")
            },
            setStyle: function(t, e) {
                this.$element.attr("class") && this.$newElement.addClass(this.$element.attr("class").replace(/selectpicker|mobile-device|bs-select-hidden|validate\[.*\]/gi, ""));
                var i = t ? t : this.options.style;
                "add" == e ? this.$button.addClass(i) : "remove" == e ? this.$button.removeClass(i) : (this.$button.removeClass(this.options.style),
                this.$button.addClass(i))
            },
            liHeight: function(e) {
                if (e || this.options.size !== !1 && !this.sizeInfo) {
                    var i = document.createElement("div")
                      , n = document.createElement("div")
                      , a = document.createElement("ul")
                      , r = document.createElement("li")
                      , o = document.createElement("li")
                      , s = document.createElement("a")
                      , l = document.createElement("span")
                      , c = this.options.header ? this.$menu.find(".popover-title")[0].cloneNode(!0) : null
                      , u = this.options.liveSearch ? document.createElement("div") : null
                      , d = this.options.actionsBox && this.multiple ? this.$menu.find(".bs-actionsbox")[0].cloneNode(!0) : null
                      , h = this.options.doneButton && this.multiple ? this.$menu.find(".bs-donebutton")[0].cloneNode(!0) : null;
                    if (l.className = "text",
                    i.className = this.$menu[0].parentNode.className + " open",
                    n.className = "dropdown-menu open",
                    a.className = "dropdown-menu inner",
                    r.className = "divider",
                    l.appendChild(document.createTextNode("Inner text")),
                    s.appendChild(l),
                    o.appendChild(s),
                    a.appendChild(o),
                    a.appendChild(r),
                    c && n.appendChild(c),
                    u) {
                        var p = document.createElement("span");
                        u.className = "bs-searchbox",
                        p.className = "form-control",
                        u.appendChild(p),
                        n.appendChild(u)
                    }
                    d && n.appendChild(d),
                    n.appendChild(a),
                    h && n.appendChild(h),
                    i.appendChild(n),
                    document.body.appendChild(i);
                    var f = s.offsetHeight
                      , m = c ? c.offsetHeight : 0
                      , g = u ? u.offsetHeight : 0
                      , v = d ? d.offsetHeight : 0
                      , _ = h ? h.offsetHeight : 0
                      , y = t(r).outerHeight(!0)
                      , b = getComputedStyle ? getComputedStyle(n) : !1
                      , x = b ? t(n) : null
                      , w = parseInt(b ? b.paddingTop : x.css("paddingTop")) + parseInt(b ? b.paddingBottom : x.css("paddingBottom")) + parseInt(b ? b.borderTopWidth : x.css("borderTopWidth")) + parseInt(b ? b.borderBottomWidth : x.css("borderBottomWidth"))
                      , T = w + parseInt(b ? b.marginTop : x.css("marginTop")) + parseInt(b ? b.marginBottom : x.css("marginBottom")) + 2;
                    document.body.removeChild(i),
                    this.sizeInfo = {
                        liHeight: f,
                        headerHeight: m,
                        searchHeight: g,
                        actionsHeight: v,
                        doneButtonHeight: _,
                        dividerHeight: y,
                        menuPadding: w,
                        menuExtras: T
                    }
                }
            },
            setSize: function() {
                this.findLis(),
                this.liHeight();
                var e, i, n, a, r = this, o = this.$menu, s = this.$menuInner, l = t(window), c = this.$newElement[0].offsetHeight, u = this.sizeInfo.liHeight, d = this.sizeInfo.headerHeight, h = this.sizeInfo.searchHeight, p = this.sizeInfo.actionsHeight, f = this.sizeInfo.doneButtonHeight, m = this.sizeInfo.dividerHeight, g = this.sizeInfo.menuPadding, v = this.sizeInfo.menuExtras, _ = this.options.hideDisabled ? ".disabled" : "", y = function() {
                    n = r.$newElement.offset().top - l.scrollTop(),
                    a = l.height() - n - c
                };
                if (y(),
                this.options.header && o.css("padding-top", 0),
                "auto" === this.options.size) {
                    var b = function() {
                        var l, c = function(e, i) {
                            return function(n) {
                                return i ? n.classList ? n.classList.contains(e) : t(n).hasClass(e) : !(n.classList ? n.classList.contains(e) : t(n).hasClass(e))
                            }
                        }, m = r.$menuInner[0].getElementsByTagName("li"), _ = Array.prototype.filter ? Array.prototype.filter.call(m, c("hidden", !1)) : r.$lis.not(".hidden"), b = Array.prototype.filter ? Array.prototype.filter.call(_, c("dropdown-header", !0)) : _.filter(".dropdown-header");
                        y(),
                        e = a - v,
                        r.options.container ? (o.data("height") || o.data("height", o.height()),
                        i = o.data("height")) : i = o.height(),
                        r.options.dropupAuto && r.$newElement.toggleClass("dropup", n > a && i > e - v),
                        r.$newElement.hasClass("dropup") && (e = n - v),
                        l = _.length + b.length > 3 ? 3 * u + v - 2 : 0,
                        o.css({
                            "max-height": e + "px",
                            overflow: "hidden",
                            "min-height": l + d + h + p + f + "px"
                        }),
                        s.css({
                            "max-height": e - d - h - p - f - g + "px",
                            "overflow-y": "auto",
                            "min-height": Math.max(l - g, 0) + "px"
                        })
                    };
                    b(),
                    this.$searchbox.off("input.getSize propertychange.getSize").on("input.getSize propertychange.getSize", b),
                    l.off("resize.getSize scroll.getSize").on("resize.getSize scroll.getSize", b)
                } else if (this.options.size && "auto" != this.options.size && this.$lis.not(_).length > this.options.size) {
                    var x = this.$lis.not(".divider").not(_).children().slice(0, this.options.size).last().parent().index()
                      , w = this.$lis.slice(0, x + 1).filter(".divider").length;
                    e = u * this.options.size + w * m + g,
                    r.options.container ? (o.data("height") || o.data("height", o.height()),
                    i = o.data("height")) : i = o.height(),
                    r.options.dropupAuto && this.$newElement.toggleClass("dropup", n > a && i > e - v),
                    o.css({
                        "max-height": e + d + h + p + f + "px",
                        overflow: "hidden",
                        "min-height": ""
                    }),
                    s.css({
                        "max-height": e - g + "px",
                        "overflow-y": "auto",
                        "min-height": ""
                    })
                }
            },
            setWidth: function() {
                if ("auto" === this.options.width) {
                    this.$menu.css("min-width", "0");
                    var t = this.$menu.parent().clone().appendTo("body")
                      , e = this.options.container ? this.$newElement.clone().appendTo("body") : t
                      , i = t.children(".dropdown-menu").outerWidth()
                      , n = e.css("width", "auto").children("button").outerWidth();
                    t.remove(),
                    e.remove(),
                    this.$newElement.css("width", Math.max(i, n) + "px")
                } else
                    "fit" === this.options.width ? (this.$menu.css("min-width", ""),
                    this.$newElement.css("width", "").addClass("fit-width")) : this.options.width ? (this.$menu.css("min-width", ""),
                    this.$newElement.css("width", this.options.width)) : (this.$menu.css("min-width", ""),
                    this.$newElement.css("width", ""));
                this.$newElement.hasClass("fit-width") && "fit" !== this.options.width && this.$newElement.removeClass("fit-width")
            },
            selectPosition: function() {
                var e, i, n = this, a = "<div />", r = t(a), o = function(t) {
                    r.addClass(t.attr("class").replace(/form-control|fit-width/gi, "")).toggleClass("dropup", t.hasClass("dropup")),
                    e = t.offset(),
                    i = t.hasClass("dropup") ? 0 : t[0].offsetHeight,
                    r.css({
                        top: e.top + i,
                        left: e.left,
                        width: t[0].offsetWidth,
                        position: "absolute"
                    })
                };
                this.$newElement.on("click", function() {
                    n.isDisabled() || (o(t(this)),
                    r.appendTo(n.options.container),
                    r.toggleClass("open", !t(this).hasClass("open")),
                    r.append(n.$menu))
                }),
                t(window).on("resize scroll", function() {
                    o(n.$newElement)
                }),
                this.$element.on("hide.bs.select", function() {
                    n.$menu.data("height", n.$menu.height()),
                    r.detach()
                })
            },
            setSelected: function(t, e, i) {
                if (!i)
                    var i = this.findLis().eq(this.liObj[t]);
                i.toggleClass("selected", e)
            },
            setDisabled: function(t, e, i) {
                if (!i)
                    var i = this.findLis().eq(this.liObj[t]);
                e ? i.addClass("disabled").children("a").attr("href", "#").attr("tabindex", -1) : i.removeClass("disabled").children("a").removeAttr("href").attr("tabindex", 0)
            },
            isDisabled: function() {
                return this.$element[0].disabled
            },
            checkDisabled: function() {
                var t = this;
                this.isDisabled() ? (this.$newElement.addClass("disabled"),
                this.$button.addClass("disabled").attr("tabindex", -1)) : (this.$button.hasClass("disabled") && (this.$newElement.removeClass("disabled"),
                this.$button.removeClass("disabled")),
                -1 != this.$button.attr("tabindex") || this.$element.data("tabindex") || this.$button.removeAttr("tabindex")),
                this.$button.click(function() {
                    return !t.isDisabled()
                })
            },
            tabIndex: function() {
                this.$element.is("[tabindex]") && (this.$element.data("tabindex", this.$element.attr("tabindex")),
                this.$button.attr("tabindex", this.$element.data("tabindex")))
            },
            clickListener: function() {
                var e = this
                  , i = t(document);
                this.$newElement.on("touchstart.dropdown", ".dropdown-menu", function(t) {
                    t.stopPropagation()
                }),
                i.data("spaceSelect", !1),
                this.$button.on("keyup", function(t) {
                    /(32)/.test(t.keyCode.toString(10)) && i.data("spaceSelect") && (t.preventDefault(),
                    i.data("spaceSelect", !1))
                }),
                this.$newElement.on("click", function() {
                    e.setSize(),
                    e.$element.on("shown.bs.select", function() {
                        if (e.options.liveSearch || e.multiple) {
                            if (!e.multiple) {
                                var t = e.liObj[e.$element[0].selectedIndex];
                                if ("number" != typeof t)
                                    return;
                                var i = e.$lis.eq(t)[0].offsetTop - e.$menuInner[0].offsetTop;
                                i = i - e.$menuInner[0].offsetHeight / 2 + e.sizeInfo.liHeight / 2,
                                e.$menuInner[0].scrollTop = i
                            }
                        } else
                            e.$menu.find(".selected a").focus()
                    })
                }),
                this.$menu.on("click", "li a", function(i) {
                    var n = t(this)
                      , a = n.parent().data("originalIndex")
                      , r = e.$element.val()
                      , o = e.$element.prop("selectedIndex");
                    if (e.multiple && i.stopPropagation(),
                    i.preventDefault(),
                    !e.isDisabled() && !n.parent().hasClass("disabled")) {
                        var s = e.$element.find("option")
                          , l = s.eq(a)
                          , c = l.prop("selected")
                          , u = l.parent("optgroup")
                          , d = e.options.maxOptions
                          , h = u.data("maxOptions") || !1;
                        if (e.multiple) {
                            if (l.prop("selected", !c),
                            e.setSelected(a, !c),
                            n.blur(),
                            d !== !1 || h !== !1) {
                                var p = d < s.filter(":selected").length
                                  , f = h < u.find("option:selected").length;
                                if (d && p || h && f)
                                    if (d && 1 == d)
                                        s.prop("selected", !1),
                                        l.prop("selected", !0),
                                        e.$menu.find(".selected").removeClass("selected"),
                                        e.setSelected(a, !0);
                                    else if (h && 1 == h) {
                                        u.find("option:selected").prop("selected", !1),
                                        l.prop("selected", !0);
                                        var m = n.parent().data("optgroup");
                                        e.$menu.find('[data-optgroup="' + m + '"]').removeClass("selected"),
                                        e.setSelected(a, !0)
                                    } else {
                                        var g = "function" == typeof e.options.maxOptionsText ? e.options.maxOptionsText(d, h) : e.options.maxOptionsText
                                          , v = g[0].replace("{n}", d)
                                          , _ = g[1].replace("{n}", h)
                                          , y = t('<div class="notify"></div>');
                                        g[2] && (v = v.replace("{var}", g[2][d > 1 ? 0 : 1]),
                                        _ = _.replace("{var}", g[2][h > 1 ? 0 : 1])),
                                        l.prop("selected", !1),
                                        e.$menu.append(y),
                                        d && p && (y.append(t("<div>" + v + "</div>")),
                                        e.$element.trigger("maxReached.bs.select")),
                                        h && f && (y.append(t("<div>" + _ + "</div>")),
                                        e.$element.trigger("maxReachedGrp.bs.select")),
                                        setTimeout(function() {
                                            e.setSelected(a, !1)
                                        }, 10),
                                        y.delay(750).fadeOut(300, function() {
                                            t(this).remove()
                                        })
                                    }
                            }
                        } else
                            s.prop("selected", !1),
                            l.prop("selected", !0),
                            e.$menu.find(".selected").removeClass("selected"),
                            e.setSelected(a, !0);
                        e.multiple ? e.options.liveSearch && e.$searchbox.focus() : e.$button.focus(),
                        (r != e.$element.val() && e.multiple || o != e.$element.prop("selectedIndex") && !e.multiple) && (e.$element.change(),
                        e.$element.trigger("changed.bs.select", [a, l.prop("selected"), c]))
                    }
                }),
                this.$menu.on("click", "li.disabled a, .popover-title, .popover-title :not(.close)", function(i) {
                    i.currentTarget == this && (i.preventDefault(),
                    i.stopPropagation(),
                    e.options.liveSearch && !t(i.target).hasClass("close") ? e.$searchbox.focus() : e.$button.focus())
                }),
                this.$menu.on("click", "li.divider, li.dropdown-header", function(t) {
                    t.preventDefault(),
                    t.stopPropagation(),
                    e.options.liveSearch ? e.$searchbox.focus() : e.$button.focus()
                }),
                this.$menu.on("click", ".popover-title .close", function() {
                    e.$button.click()
                }),
                this.$searchbox.on("click", function(t) {
                    t.stopPropagation()
                }),
                this.$menu.on("click", ".actions-btn", function(i) {
                    e.options.liveSearch ? e.$searchbox.focus() : e.$button.focus(),
                    i.preventDefault(),
                    i.stopPropagation(),
                    t(this).hasClass("bs-select-all") ? e.selectAll() : e.deselectAll(),
                    e.$element.change()
                }),
                this.$element.change(function() {
                    e.render(!1)
                })
            },
            liveSearchListener: function() {
                var n = this
                  , a = t('<li class="no-results"></li>');
                this.$newElement.on("click.dropdown.data-api touchstart.dropdown.data-api", function() {
                    n.$menuInner.find(".active").removeClass("active"),
                    n.$searchbox.val() && (n.$searchbox.val(""),
                    n.$lis.not(".is-hidden").removeClass("hidden"),
                    a.parent().length && a.remove()),
                    n.multiple || n.$menuInner.find(".selected").addClass("active"),
                    setTimeout(function() {
                        n.$searchbox.focus()
                    }, 10)
                }),
                this.$searchbox.on("click.dropdown.data-api focus.dropdown.data-api touchend.dropdown.data-api", function(t) {
                    t.stopPropagation()
                }),
                this.$searchbox.on("input propertychange", function() {
                    if (n.$searchbox.val()) {
                        var r = n.$lis.not(".is-hidden").removeClass("hidden").children("a");
                        r = r.not(n.options.liveSearchNormalize ? ":a" + n._searchStyle() + "(" + e(n.$searchbox.val()) + ")" : ":" + n._searchStyle() + "(" + n.$searchbox.val() + ")"),
                        r.parent().addClass("hidden"),
                        n.$lis.filter(".dropdown-header").each(function() {
                            var e = t(this)
                              , i = e.data("optgroup");
                            0 === n.$lis.filter("[data-optgroup=" + i + "]").not(e).not(".hidden").length && (e.addClass("hidden"),
                            n.$lis.filter("[data-optgroup=" + i + "div]").addClass("hidden"))
                        });
                        var o = n.$lis.not(".hidden");
                        o.each(function(e) {
                            var i = t(this);
                            i.hasClass("divider") && (i.index() === o.eq(0).index() || i.index() === o.last().index() || o.eq(e + 1).hasClass("divider")) && i.addClass("hidden")
                        }),
                        n.$lis.not(".hidden, .no-results").length ? a.parent().length && a.remove() : (a.parent().length && a.remove(),
                        a.html(n.options.noneResultsText.replace("{0}", '"' + i(n.$searchbox.val()) + '"')).show(),
                        n.$menuInner.append(a))
                    } else
                        n.$lis.not(".is-hidden").removeClass("hidden"),
                        a.parent().length && a.remove();
                    n.$lis.filter(".active").removeClass("active"),
                    n.$lis.not(".hidden, .divider, .dropdown-header").eq(0).addClass("active").children("a").focus(),
                    t(this).focus()
                })
            },
            _searchStyle: function() {
                var t = "icontains";
                switch (this.options.liveSearchStyle) {
                case "begins":
                case "startsWith":
                    t = "ibegins";
                    break;
                case "contains":
                }
                return t
            },
            val: function(t) {
                return "undefined" != typeof t ? (this.$element.val(t),
                this.render(),
                this.$element) : this.$element.val()
            },
            selectAll: function() {
                this.findLis(),
                this.$element.find("option:enabled").not("[data-divider], [data-hidden]").prop("selected", !0),
                this.$lis.not(".divider, .dropdown-header, .disabled, .hidden").addClass("selected"),
                this.render(!1)
            },
            deselectAll: function() {
                this.findLis(),
                this.$element.find("option:enabled").not("[data-divider], [data-hidden]").prop("selected", !1),
                this.$lis.not(".divider, .dropdown-header, .disabled, .hidden").removeClass("selected"),
                this.render(!1)
            },
            keydown: function(i) {
                var n, a, r, o, s, l, c, u, d, h = t(this), p = h.is("input") ? h.parent().parent() : h.parent(), f = p.data("this"), m = ":not(.disabled, .hidden, .dropdown-header, .divider)", g = {
                    32: " ",
                    48: "0",
                    49: "1",
                    50: "2",
                    51: "3",
                    52: "4",
                    53: "5",
                    54: "6",
                    55: "7",
                    56: "8",
                    57: "9",
                    59: ";",
                    65: "a",
                    66: "b",
                    67: "c",
                    68: "d",
                    69: "e",
                    70: "f",
                    71: "g",
                    72: "h",
                    73: "i",
                    74: "j",
                    75: "k",
                    76: "l",
                    77: "m",
                    78: "n",
                    79: "o",
                    80: "p",
                    81: "q",
                    82: "r",
                    83: "s",
                    84: "t",
                    85: "u",
                    86: "v",
                    87: "w",
                    88: "x",
                    89: "y",
                    90: "z",
                    96: "0",
                    97: "1",
                    98: "2",
                    99: "3",
                    100: "4",
                    101: "5",
                    102: "6",
                    103: "7",
                    104: "8",
                    105: "9"
                };
                if (f.options.liveSearch && (p = h.parent().parent()),
                f.options.container && (p = f.$menu),
                n = t("[role=menu] li a", p),
                d = f.$menu.parent().hasClass("open"),
                !d && (i.keyCode >= 48 && i.keyCode <= 57 || event.keyCode >= 65 && event.keyCode <= 90) && (f.options.container ? f.$newElement.trigger("click") : (f.setSize(),
                f.$menu.parent().addClass("open"),
                d = !0),
                f.$searchbox.focus()),
                f.options.liveSearch && (/(^9$|27)/.test(i.keyCode.toString(10)) && d && 0 === f.$menu.find(".active").length && (i.preventDefault(),
                f.$menu.parent().removeClass("open"),
                f.options.container && f.$newElement.removeClass("open"),
                f.$button.focus()),
                n = t("[role=menu] li:not(.disabled, .hidden, .dropdown-header, .divider)", p),
                h.val() || /(38|40)/.test(i.keyCode.toString(10)) || 0 === n.filter(".active").length && (n = f.$newElement.find("li"),
                n = n.filter(f.options.liveSearchNormalize ? ":a" + f._searchStyle() + "(" + e(g[i.keyCode]) + ")" : ":" + f._searchStyle() + "(" + g[i.keyCode] + ")"))),
                n.length) {
                    if (/(38|40)/.test(i.keyCode.toString(10)))
                        a = n.index(n.filter(":focus")),
                        o = n.parent(m).first().data("originalIndex"),
                        s = n.parent(m).last().data("originalIndex"),
                        r = n.eq(a).parent().nextAll(m).eq(0).data("originalIndex"),
                        l = n.eq(a).parent().prevAll(m).eq(0).data("originalIndex"),
                        c = n.eq(r).parent().prevAll(m).eq(0).data("originalIndex"),
                        f.options.liveSearch && (n.each(function(e) {
                            t(this).hasClass("disabled") || t(this).data("index", e)
                        }),
                        a = n.index(n.filter(".active")),
                        o = n.first().data("index"),
                        s = n.last().data("index"),
                        r = n.eq(a).nextAll().eq(0).data("index"),
                        l = n.eq(a).prevAll().eq(0).data("index"),
                        c = n.eq(r).prevAll().eq(0).data("index")),
                        u = h.data("prevIndex"),
                        38 == i.keyCode ? (f.options.liveSearch && (a -= 1),
                        a != c && a > l && (a = l),
                        o > a && (a = o),
                        a == u && (a = s)) : 40 == i.keyCode && (f.options.liveSearch && (a += 1),
                        -1 == a && (a = 0),
                        a != c && r > a && (a = r),
                        a > s && (a = s),
                        a == u && (a = o)),
                        h.data("prevIndex", a),
                        f.options.liveSearch ? (i.preventDefault(),
                        h.hasClass("dropdown-toggle") || (n.removeClass("active").eq(a).addClass("active").children("a").focus(),
                        h.focus())) : n.eq(a).focus();
                    else if (!h.is("input")) {
                        var v, _, y = [];
                        n.each(function() {
                            t(this).parent().hasClass("disabled") || t.trim(t(this).text().toLowerCase()).substring(0, 1) == g[i.keyCode] && y.push(t(this).parent().index())
                        }),
                        v = t(document).data("keycount"),
                        v++,
                        t(document).data("keycount", v),
                        _ = t.trim(t(":focus").text().toLowerCase()).substring(0, 1),
                        _ != g[i.keyCode] ? (v = 1,
                        t(document).data("keycount", v)) : v >= y.length && (t(document).data("keycount", 0),
                        v > y.length && (v = 1)),
                        n.eq(y[v - 1]).focus()
                    }
                    if ((/(13|32)/.test(i.keyCode.toString(10)) || /(^9$)/.test(i.keyCode.toString(10)) && f.options.selectOnTab) && d) {
                        if (/(32)/.test(i.keyCode.toString(10)) || i.preventDefault(),
                        f.options.liveSearch)
                            /(32)/.test(i.keyCode.toString(10)) || (f.$menu.find(".active a").click(),
                            h.focus());
                        else {
                            var b = t(":focus");
                            b.click(),
                            b.focus(),
                            i.preventDefault(),
                            t(document).data("spaceSelect", !0)
                        }
                        t(document).data("keycount", 0)
                    }
                    (/(^9$|27)/.test(i.keyCode.toString(10)) && d && (f.multiple || f.options.liveSearch) || /(27)/.test(i.keyCode.toString(10)) && !d) && (f.$menu.parent().removeClass("open"),
                    f.options.container && f.$newElement.removeClass("open"),
                    f.$button.focus())
                }
            },
            mobile: function() {
                this.$element.addClass("mobile-device").appendTo(this.$newElement),
                this.options.container && this.$menu.hide()
            },
            refresh: function() {
                this.$lis = null,
                this.reloadLi(),
                this.render(),
                this.checkDisabled(),
                this.liHeight(!0),
                this.setStyle(),
                this.setWidth(),
                this.$lis && this.$searchbox.trigger("propertychange"),
                this.$element.trigger("refreshed.bs.select")
            },
            hide: function() {
                this.$newElement.hide()
            },
            show: function() {
                this.$newElement.show()
            },
            remove: function() {
                this.$newElement.remove(),
                this.$element.remove()
            }
        };
        var r = t.fn.selectpicker;
        t.fn.selectpicker = n,
        t.fn.selectpicker.Constructor = a,
        t.fn.selectpicker.noConflict = function() {
            return t.fn.selectpicker = r,
            this
        }
        ,
        t(document).data("keycount", 0).on("keydown", '.bootstrap-select [data-toggle=dropdown], .bootstrap-select [role="menu"], .bs-searchbox input', a.prototype.keydown).on("focusin.modal", '.bootstrap-select [data-toggle=dropdown], .bootstrap-select [role="menu"], .bs-searchbox input', function(t) {
            t.stopPropagation()
        }),
        t(window).on("load.bs.select.data-api", function() {
            t(".selectpicker").each(function() {
                var e = t(this);
                n.call(e, e.data())
            })
        })
    }(jQuery)
}),
function(t, e) {
    function i() {
        return new Date(Date.UTC.apply(Date, arguments))
    }
    function n() {
        var t = new Date;
        return i(t.getFullYear(), t.getMonth(), t.getDate())
    }
    function a(t, e) {
        return t.getUTCFullYear() === e.getUTCFullYear() && t.getUTCMonth() === e.getUTCMonth() && t.getUTCDate() === e.getUTCDate()
    }
    function r(t) {
        return function() {
            return this[t].apply(this, arguments)
        }
    }
    function o(e, i) {
        function n(t, e) {
            return e.toLowerCase()
        }
        var a, r = t(e).data(), o = {}, s = new RegExp("^" + i.toLowerCase() + "([A-Z])");
        i = new RegExp("^" + i.toLowerCase());
        for (var l in r)
            i.test(l) && (a = l.replace(s, n),
            o[a] = r[l]);
        return o
    }
    function s(e) {
        var i = {};
        if (m[e] || (e = e.split("-")[0],
        m[e])) {
            var n = m[e];
            return t.each(f, function(t, e) {
                e in n && (i[e] = n[e])
            }),
            i
        }
    }
    var l = function() {
        var e = {
            get: function(t) {
                return this.slice(t)[0]
            },
            contains: function(t) {
                for (var e = t && t.valueOf(), i = 0, n = this.length; n > i; i++)
                    if (this[i].valueOf() === e)
                        return i;
                return -1
            },
            remove: function(t) {
                this.splice(t, 1)
            },
            replace: function(e) {
                e && (t.isArray(e) || (e = [e]),
                this.clear(),
                this.push.apply(this, e))
            },
            clear: function() {
                this.length = 0
            },
            copy: function() {
                var t = new l;
                return t.replace(this),
                t
            }
        };
        return function() {
            var i = [];
            return i.push.apply(i, arguments),
            t.extend(i, e),
            i
        }
    }()
      , c = function(e, i) {
        this._process_options(i),
        this.dates = new l,
        this.viewDate = this.o.defaultViewDate,
        this.focusDate = null,
        this.element = t(e),
        this.isInline = !1,
        this.isInput = this.element.is("input"),
        this.component = this.element.hasClass("date") ? this.element.find(".add-on, .input-group-addon, .btn") : !1,
        this.hasInput = this.component && this.element.find("input").length,
        this.component && 0 === this.component.length && (this.component = !1),
        this.picker = t(g.template),
        this._buildEvents(),
        this._attachEvents(),
        this.isInline ? this.picker.addClass("datepicker-inline").appendTo(this.element) : this.picker.addClass("datepicker-dropdown dropdown-menu"),
        this.o.rtl && this.picker.addClass("datepicker-rtl"),
        this.viewMode = this.o.startView,
        this.o.calendarWeeks && this.picker.find("tfoot .today, tfoot .clear").attr("colspan", function(t, e) {
            return parseInt(e) + 1
        }),
        this._allow_update = !1,
        this.setStartDate(this._o.startDate),
        this.setEndDate(this._o.endDate),
        this.setDaysOfWeekDisabled(this.o.daysOfWeekDisabled),
        this.setDatesDisabled(this.o.datesDisabled),
        this.fillDow(),
        this.fillMonths(),
        this._allow_update = !0,
        this.update(),
        this.showMode(),
        this.isInline && this.show()
    };
    c.prototype = {
        constructor: c,
        _process_options: function(a) {
            this._o = t.extend({}, this._o, a);
            var r = this.o = t.extend({}, this._o)
              , o = r.language;
            switch (m[o] || (o = o.split("-")[0],
            m[o] || (o = p.language)),
            r.language = o,
            r.startView) {
            case 2:
            case "decade":
                r.startView = 2;
                break;
            case 1:
            case "year":
                r.startView = 1;
                break;
            default:
                r.startView = 0
            }
            switch (r.minViewMode) {
            case 1:
            case "months":
                r.minViewMode = 1;
                break;
            case 2:
            case "years":
                r.minViewMode = 2;
                break;
            default:
                r.minViewMode = 0
            }
            r.startView = Math.max(r.startView, r.minViewMode),
            r.multidate !== !0 && (r.multidate = Number(r.multidate) || !1,
            r.multidate !== !1 && (r.multidate = Math.max(0, r.multidate))),
            r.multidateSeparator = String(r.multidateSeparator),
            r.weekStart %= 7,
            r.weekEnd = (r.weekStart + 6) % 7;
            var s = g.parseFormat(r.format);
            if (r.startDate !== -1 / 0 && (r.startDate = r.startDate ? r.startDate instanceof Date ? this._local_to_utc(this._zero_time(r.startDate)) : g.parseDate(r.startDate, s, r.language) : -1 / 0),
            1 / 0 !== r.endDate && (r.endDate = r.endDate ? r.endDate instanceof Date ? this._local_to_utc(this._zero_time(r.endDate)) : g.parseDate(r.endDate, s, r.language) : 1 / 0),
            r.daysOfWeekDisabled = r.daysOfWeekDisabled || [],
            t.isArray(r.daysOfWeekDisabled) || (r.daysOfWeekDisabled = r.daysOfWeekDisabled.split(/[,\s]*/)),
            r.daysOfWeekDisabled = t.map(r.daysOfWeekDisabled, function(t) {
                return parseInt(t, 10)
            }),
            r.datesDisabled = r.datesDisabled || [],
            !t.isArray(r.datesDisabled)) {
                var l = [];
                l.push(g.parseDate(r.datesDisabled, s, r.language)),
                r.datesDisabled = l
            }
            r.datesDisabled = t.map(r.datesDisabled, function(t) {
                return g.parseDate(t, s, r.language)
            });
            var c = String(r.orientation).toLowerCase().split(/\s+/g)
              , u = r.orientation.toLowerCase();
            if (c = t.grep(c, function(t) {
                return /^auto|left|right|top|bottom$/.test(t)
            }),
            r.orientation = {
                x: "auto",
                y: "auto"
            },
            u && "auto" !== u)
                if (1 === c.length)
                    switch (c[0]) {
                    case "top":
                    case "bottom":
                        r.orientation.y = c[0];
                        break;
                    case "left":
                    case "right":
                        r.orientation.x = c[0]
                    }
                else
                    u = t.grep(c, function(t) {
                        return /^left|right$/.test(t)
                    }),
                    r.orientation.x = u[0] || "auto",
                    u = t.grep(c, function(t) {
                        return /^top|bottom$/.test(t)
                    }),
                    r.orientation.y = u[0] || "auto";
            else
                ;if (r.defaultViewDate) {
                var d = r.defaultViewDate.year || (new Date).getFullYear()
                  , h = r.defaultViewDate.month || 0
                  , f = r.defaultViewDate.day || 1;
                r.defaultViewDate = i(d, h, f)
            } else
                r.defaultViewDate = n();
            r.showOnFocus = r.showOnFocus !== e ? r.showOnFocus : !0
        },
        _events: [],
        _secondaryEvents: [],
        _applyEvents: function(t) {
            for (var i, n, a, r = 0; r < t.length; r++)
                i = t[r][0],
                2 === t[r].length ? (n = e,
                a = t[r][1]) : 3 === t[r].length && (n = t[r][1],
                a = t[r][2]),
                i.on(a, n)
        },
        _unapplyEvents: function(t) {
            for (var i, n, a, r = 0; r < t.length; r++)
                i = t[r][0],
                2 === t[r].length ? (a = e,
                n = t[r][1]) : 3 === t[r].length && (a = t[r][1],
                n = t[r][2]),
                i.off(n, a)
        },
        _buildEvents: function() {
            var e = {
                keyup: t.proxy(function(e) {
                    -1 === t.inArray(e.keyCode, [27, 37, 39, 38, 40, 32, 13, 9]) && this.update()
                }, this),
                keydown: t.proxy(this.keydown, this)
            };
            this.o.showOnFocus === !0 && (e.focus = t.proxy(this.show, this)),
            this.isInput ? this._events = [[this.element, e]] : this.component && this.hasInput ? this._events = [[this.element.find("input"), e], [this.component, {
                click: t.proxy(this.show, this)
            }]] : this.element.is("div") ? this.isInline = !0 : this._events = [[this.element, {
                click: t.proxy(this.show, this)
            }]],
            this._events.push([this.element, "*", {
                blur: t.proxy(function(t) {
                    this._focused_from = t.target
                }, this)
            }], [this.element, {
                blur: t.proxy(function(t) {
                    this._focused_from = t.target
                }, this)
            }]),
            this._secondaryEvents = [[this.picker, {
                click: t.proxy(this.click, this)
            }], [t(window), {
                resize: t.proxy(this.place, this)
            }], [t(document), {
                "mousedown touchstart": t.proxy(function(t) {
                    this.element.is(t.target) || this.element.find(t.target).length || this.picker.is(t.target) || this.picker.find(t.target).length || this.hide()
                }, this)
            }]]
        },
        _attachEvents: function() {
            this._detachEvents(),
            this._applyEvents(this._events)
        },
        _detachEvents: function() {
            this._unapplyEvents(this._events)
        },
        _attachSecondaryEvents: function() {
            this._detachSecondaryEvents(),
            this._applyEvents(this._secondaryEvents)
        },
        _detachSecondaryEvents: function() {
            this._unapplyEvents(this._secondaryEvents)
        },
        _trigger: function(e, i) {
            var n = i || this.dates.get(-1)
              , a = this._utc_to_local(n);
            this.element.trigger({
                type: e,
                date: a,
                dates: t.map(this.dates, this._utc_to_local),
                format: t.proxy(function(t, e) {
                    0 === arguments.length ? (t = this.dates.length - 1,
                    e = this.o.format) : "string" == typeof t && (e = t,
                    t = this.dates.length - 1),
                    e = e || this.o.format;
                    var i = this.dates.get(t);
                    return g.formatDate(i, e, this.o.language)
                }, this)
            })
        },
        show: function() {
            return this.element.attr("readonly") && this.o.enableOnReadonly === !1 ? void 0 : (this.isInline || this.picker.appendTo(this.o.container),
            this.place(),
            this.picker.show(),
            this._attachSecondaryEvents(),
            this._trigger("show"),
            (window.navigator.msMaxTouchPoints || "ontouchstart"in document) && this.o.disableTouchKeyboard && t(this.element).blur(),
            this)
        },
        hide: function() {
            return this.isInline ? this : this.picker.is(":visible") ? (this.focusDate = null,
            this.picker.hide().detach(),
            this._detachSecondaryEvents(),
            this.viewMode = this.o.startView,
            this.showMode(),
            this.o.forceParse && (this.isInput && this.element.val() || this.hasInput && this.element.find("input").val()) && this.setValue(),
            this._trigger("hide"),
            this) : this
        },
        remove: function() {
            return this.hide(),
            this._detachEvents(),
            this._detachSecondaryEvents(),
            this.picker.remove(),
            delete this.element.data().datepicker,
            this.isInput || delete this.element.data().date,
            this
        },
        _utc_to_local: function(t) {
            return t && new Date(t.getTime() + 6e4 * t.getTimezoneOffset())
        },
        _local_to_utc: function(t) {
            return t && new Date(t.getTime() - 6e4 * t.getTimezoneOffset())
        },
        _zero_time: function(t) {
            return t && new Date(t.getFullYear(),t.getMonth(),t.getDate())
        },
        _zero_utc_time: function(t) {
            return t && new Date(Date.UTC(t.getUTCFullYear(), t.getUTCMonth(), t.getUTCDate()))
        },
        getDates: function() {
            return t.map(this.dates, this._utc_to_local)
        },
        getUTCDates: function() {
            return t.map(this.dates, function(t) {
                return new Date(t)
            })
        },
        getDate: function() {
            return this._utc_to_local(this.getUTCDate())
        },
        getUTCDate: function() {
            var t = this.dates.get(-1);
            return "undefined" != typeof t ? new Date(t) : null
        },
        clearDates: function() {
            var t;
            this.isInput ? t = this.element : this.component && (t = this.element.find("input")),
            t && t.val("").change(),
            this.update(),
            this._trigger("changeDate"),
            this.o.autoclose && this.hide()
        },
        setDates: function() {
            var e = t.isArray(arguments[0]) ? arguments[0] : arguments;
            return this.update.apply(this, e),
            this._trigger("changeDate"),
            this.setValue(),
            this
        },
        setUTCDates: function() {
            var e = t.isArray(arguments[0]) ? arguments[0] : arguments;
            return this.update.apply(this, t.map(e, this._utc_to_local)),
            this._trigger("changeDate"),
            this.setValue(),
            this
        },
        setDate: r("setDates"),
        setUTCDate: r("setUTCDates"),
        setValue: function() {
            var t = this.getFormattedDate();
            return this.isInput ? this.element.val(t).change() : this.component && this.element.find("input").val(t).change(),
            this
        },
        getFormattedDate: function(i) {
            i === e && (i = this.o.format);
            var n = this.o.language;
            return t.map(this.dates, function(t) {
                return g.formatDate(t, i, n)
            }).join(this.o.multidateSeparator)
        },
        setStartDate: function(t) {
            return this._process_options({
                startDate: t
            }),
            this.update(),
            this.updateNavArrows(),
            this
        },
        setEndDate: function(t) {
            return this._process_options({
                endDate: t
            }),
            this.update(),
            this.updateNavArrows(),
            this
        },
        setDaysOfWeekDisabled: function(t) {
            return this._process_options({
                daysOfWeekDisabled: t
            }),
            this.update(),
            this.updateNavArrows(),
            this
        },
        setDatesDisabled: function(t) {
            this._process_options({
                datesDisabled: t
            }),
            this.update(),
            this.updateNavArrows()
        },
        place: function() {
            if (this.isInline)
                return this;
            var e = this.picker.outerWidth()
              , i = this.picker.outerHeight()
              , n = 10
              , a = t(this.o.container).width()
              , r = t(this.o.container).height()
              , o = t(this.o.container).scrollTop()
              , s = t(this.o.container).offset()
              , l = [];
            this.element.parents().each(function() {
                var e = t(this).css("z-index");
                "auto" !== e && 0 !== e && l.push(parseInt(e))
            });
            var c = Math.max.apply(Math, l) + 10
              , u = this.component ? this.component.parent().offset() : this.element.offset()
              , d = this.component ? this.component.outerHeight(!0) : this.element.outerHeight(!1)
              , h = this.component ? this.component.outerWidth(!0) : this.element.outerWidth(!1)
              , p = u.left - s.left
              , f = u.top - s.top;
            this.picker.removeClass("datepicker-orient-top datepicker-orient-bottom datepicker-orient-right datepicker-orient-left"),
            "auto" !== this.o.orientation.x ? (this.picker.addClass("datepicker-orient-" + this.o.orientation.x),
            "right" === this.o.orientation.x && (p -= e - h)) : u.left < 0 ? (this.picker.addClass("datepicker-orient-left"),
            p -= u.left - n) : p + e > a ? (this.picker.addClass("datepicker-orient-right"),
            p = u.left + h - e) : this.picker.addClass("datepicker-orient-left");
            var m, g, v = this.o.orientation.y;
            if ("auto" === v && (m = -o + f - i,
            g = o + r - (f + d + i),
            v = Math.max(m, g) === g ? "top" : "bottom"),
            this.picker.addClass("datepicker-orient-" + v),
            "top" === v ? f += d : f -= i + parseInt(this.picker.css("padding-top")),
            this.o.rtl) {
                var _ = a - (p + h);
                this.picker.css({
                    top: f,
                    right: _,
                    zIndex: c
                })
            } else
                this.picker.css({
                    top: f,
                    left: p,
                    zIndex: c
                });
            return this
        },
        _allow_update: !0,
        update: function() {
            if (!this._allow_update)
                return this;
            var e = this.dates.copy()
              , i = []
              , n = !1;
            return arguments.length ? (t.each(arguments, t.proxy(function(t, e) {
                e instanceof Date && (e = this._local_to_utc(e)),
                i.push(e)
            }, this)),
            n = !0) : (i = this.isInput ? this.element.val() : this.element.data("date") || this.element.find("input").val(),
            i = i && this.o.multidate ? i.split(this.o.multidateSeparator) : [i],
            delete this.element.data().date),
            i = t.map(i, t.proxy(function(t) {
                return g.parseDate(t, this.o.format, this.o.language)
            }, this)),
            i = t.grep(i, t.proxy(function(t) {
                return t < this.o.startDate || t > this.o.endDate || !t
            }, this), !0),
            this.dates.replace(i),
            this.dates.length ? this.viewDate = new Date(this.dates.get(-1)) : this.viewDate < this.o.startDate ? this.viewDate = new Date(this.o.startDate) : this.viewDate > this.o.endDate && (this.viewDate = new Date(this.o.endDate)),
            n ? this.setValue() : i.length && String(e) !== String(this.dates) && this._trigger("changeDate"),
            !this.dates.length && e.length && this._trigger("clearDate"),
            this.fill(),
            this
        },
        fillDow: function() {
            var t = this.o.weekStart
              , e = "<tr>";
            if (this.o.calendarWeeks) {
                this.picker.find(".datepicker-days thead tr:first-child .datepicker-switch").attr("colspan", function(t, e) {
                    return parseInt(e) + 1
                });
                var i = '<th class="cw">&#160;</th>';
                e += i
            }
            for (; t < this.o.weekStart + 7; )
                e += '<th class="dow">' + m[this.o.language].daysMin[t++ % 7] + "</th>";
            e += "</tr>",
            this.picker.find(".datepicker-days thead").append(e)
        },
        fillMonths: function() {
            for (var t = "", e = 0; 12 > e; )
                t += '<span class="month">' + m[this.o.language].monthsShort[e++] + "</span>";
            this.picker.find(".datepicker-months td").html(t)
        },
        setRange: function(e) {
            e && e.length ? this.range = t.map(e, function(t) {
                return t.valueOf()
            }) : delete this.range,
            this.fill()
        },
        getClassNames: function(e) {
            var i = []
              , n = this.viewDate.getUTCFullYear()
              , r = this.viewDate.getUTCMonth()
              , o = new Date;
            return e.getUTCFullYear() < n || e.getUTCFullYear() === n && e.getUTCMonth() < r ? i.push("old") : (e.getUTCFullYear() > n || e.getUTCFullYear() === n && e.getUTCMonth() > r) && i.push("new"),
            this.focusDate && e.valueOf() === this.focusDate.valueOf() && i.push("focused"),
            this.o.todayHighlight && e.getUTCFullYear() === o.getFullYear() && e.getUTCMonth() === o.getMonth() && e.getUTCDate() === o.getDate() && i.push("today"),
            -1 !== this.dates.contains(e) && i.push("active"),
            (e.valueOf() < this.o.startDate || e.valueOf() > this.o.endDate || -1 !== t.inArray(e.getUTCDay(), this.o.daysOfWeekDisabled)) && i.push("disabled"),
            this.o.datesDisabled.length > 0 && t.grep(this.o.datesDisabled, function(t) {
                return a(e, t)
            }).length > 0 && i.push("disabled", "disabled-date"),
            this.range && (e > this.range[0] && e < this.range[this.range.length - 1] && i.push("range"),
            -1 !== t.inArray(e.valueOf(), this.range) && i.push("selected")),
            i
        },
        fill: function() {
            var n, a = new Date(this.viewDate), r = a.getUTCFullYear(), o = a.getUTCMonth(), s = this.o.startDate !== -1 / 0 ? this.o.startDate.getUTCFullYear() : -1 / 0, l = this.o.startDate !== -1 / 0 ? this.o.startDate.getUTCMonth() : -1 / 0, c = 1 / 0 !== this.o.endDate ? this.o.endDate.getUTCFullYear() : 1 / 0, u = 1 / 0 !== this.o.endDate ? this.o.endDate.getUTCMonth() : 1 / 0, d = m[this.o.language].today || m.en.today || "", h = m[this.o.language].clear || m.en.clear || "";
            if (!isNaN(r) && !isNaN(o)) {
                this.picker.find(".datepicker-days thead .datepicker-switch").text(m[this.o.language].months[o] + " " + r),
                this.picker.find("tfoot .today").text(d).toggle(this.o.todayBtn !== !1),
                this.picker.find("tfoot .clear").text(h).toggle(this.o.clearBtn !== !1),
                this.updateNavArrows(),
                this.fillMonths();
                var p = i(r, o - 1, 28)
                  , f = g.getDaysInMonth(p.getUTCFullYear(), p.getUTCMonth());
                p.setUTCDate(f),
                p.setUTCDate(f - (p.getUTCDay() - this.o.weekStart + 7) % 7);
                var v = new Date(p);
                v.setUTCDate(v.getUTCDate() + 42),
                v = v.valueOf();
                for (var _, y = []; p.valueOf() < v; ) {
                    if (p.getUTCDay() === this.o.weekStart && (y.push("<tr>"),
                    this.o.calendarWeeks)) {
                        var b = new Date(+p + (this.o.weekStart - p.getUTCDay() - 7) % 7 * 864e5)
                          , x = new Date(Number(b) + (11 - b.getUTCDay()) % 7 * 864e5)
                          , w = new Date(Number(w = i(x.getUTCFullYear(), 0, 1)) + (11 - w.getUTCDay()) % 7 * 864e5)
                          , T = (x - w) / 864e5 / 7 + 1;
                        y.push('<td class="cw">' + T + "</td>")
                    }
                    if (_ = this.getClassNames(p),
                    _.push("day"),
                    this.o.beforeShowDay !== t.noop) {
                        var C = this.o.beforeShowDay(this._utc_to_local(p));
                        C === e ? C = {} : "boolean" == typeof C ? C = {
                            enabled: C
                        } : "string" == typeof C && (C = {
                            classes: C
                        }),
                        C.enabled === !1 && _.push("disabled"),
                        C.classes && (_ = _.concat(C.classes.split(/\s+/))),
                        C.tooltip && (n = C.tooltip)
                    }
                    _ = t.unique(_),
                    y.push('<td class="' + _.join(" ") + '"' + (n ? ' title="' + n + '"' : "") + ">" + p.getUTCDate() + "</td>"),
                    n = null,
                    p.getUTCDay() === this.o.weekEnd && y.push("</tr>"),
                    p.setUTCDate(p.getUTCDate() + 1)
                }
                this.picker.find(".datepicker-days tbody").empty().append(y.join(""));
                var S = this.picker.find(".datepicker-months").find("th:eq(1)").text(r).end().find("span").removeClass("active");
                if (t.each(this.dates, function(t, e) {
                    e.getUTCFullYear() === r && S.eq(e.getUTCMonth()).addClass("active")
                }),
                (s > r || r > c) && S.addClass("disabled"),
                r === s && S.slice(0, l).addClass("disabled"),
                r === c && S.slice(u + 1).addClass("disabled"),
                this.o.beforeShowMonth !== t.noop) {
                    var k = this;
                    t.each(S, function(e, i) {
                        if (!t(i).hasClass("disabled")) {
                            var n = new Date(r,e,1)
                              , a = k.o.beforeShowMonth(n);
                            a === !1 && t(i).addClass("disabled")
                        }
                    })
                }
                y = "",
                r = 10 * parseInt(r / 10, 10);
                var $ = this.picker.find(".datepicker-years").find("th:eq(1)").text(r + "-" + (r + 9)).end().find("td");
                r -= 1;
                for (var D, A = t.map(this.dates, function(t) {
                    return t.getUTCFullYear()
                }), O = -1; 11 > O; O++)
                    D = ["year"],
                    -1 === O ? D.push("old") : 10 === O && D.push("new"),
                    -1 !== t.inArray(r, A) && D.push("active"),
                    (s > r || r > c) && D.push("disabled"),
                    y += '<span class="' + D.join(" ") + '">' + r + "</span>",
                    r += 1;
                $.html(y)
            }
        },
        updateNavArrows: function() {
            if (this._allow_update) {
                var t = new Date(this.viewDate)
                  , e = t.getUTCFullYear()
                  , i = t.getUTCMonth();
                switch (this.viewMode) {
                case 0:
                    this.picker.find(".prev").css(this.o.startDate !== -1 / 0 && e <= this.o.startDate.getUTCFullYear() && i <= this.o.startDate.getUTCMonth() ? {
                        visibility: "hidden"
                    } : {
                        visibility: "visible"
                    }),
                    this.picker.find(".next").css(1 / 0 !== this.o.endDate && e >= this.o.endDate.getUTCFullYear() && i >= this.o.endDate.getUTCMonth() ? {
                        visibility: "hidden"
                    } : {
                        visibility: "visible"
                    });
                    break;
                case 1:
                case 2:
                    this.picker.find(".prev").css(this.o.startDate !== -1 / 0 && e <= this.o.startDate.getUTCFullYear() ? {
                        visibility: "hidden"
                    } : {
                        visibility: "visible"
                    }),
                    this.picker.find(".next").css(1 / 0 !== this.o.endDate && e >= this.o.endDate.getUTCFullYear() ? {
                        visibility: "hidden"
                    } : {
                        visibility: "visible"
                    })
                }
            }
        },
        click: function(e) {
            e.preventDefault();
            var n, a, r, o = t(e.target).closest("span, td, th");
            if (1 === o.length)
                switch (o[0].nodeName.toLowerCase()) {
                case "th":
                    switch (o[0].className) {
                    case "datepicker-switch":
                        this.showMode(1);
                        break;
                    case "prev":
                    case "next":
                        var s = g.modes[this.viewMode].navStep * ("prev" === o[0].className ? -1 : 1);
                        switch (this.viewMode) {
                        case 0:
                            this.viewDate = this.moveMonth(this.viewDate, s),
                            this._trigger("changeMonth", this.viewDate);
                            break;
                        case 1:
                        case 2:
                            this.viewDate = this.moveYear(this.viewDate, s),
                            1 === this.viewMode && this._trigger("changeYear", this.viewDate)
                        }
                        this.fill();
                        break;
                    case "today":
                        var l = new Date;
                        l = i(l.getFullYear(), l.getMonth(), l.getDate(), 0, 0, 0),
                        this.showMode(-2);
                        var c = "linked" === this.o.todayBtn ? null : "view";
                        this._setDate(l, c);
                        break;
                    case "clear":
                        this.clearDates()
                    }
                    break;
                case "span":
                    o.hasClass("disabled") || (this.viewDate.setUTCDate(1),
                    o.hasClass("month") ? (r = 1,
                    a = o.parent().find("span").index(o),
                    n = this.viewDate.getUTCFullYear(),
                    this.viewDate.setUTCMonth(a),
                    this._trigger("changeMonth", this.viewDate),
                    1 === this.o.minViewMode && this._setDate(i(n, a, r))) : (r = 1,
                    a = 0,
                    n = parseInt(o.text(), 10) || 0,
                    this.viewDate.setUTCFullYear(n),
                    this._trigger("changeYear", this.viewDate),
                    2 === this.o.minViewMode && this._setDate(i(n, a, r))),
                    this.showMode(-1),
                    this.fill());
                    break;
                case "td":
                    o.hasClass("day") && !o.hasClass("disabled") && (r = parseInt(o.text(), 10) || 1,
                    n = this.viewDate.getUTCFullYear(),
                    a = this.viewDate.getUTCMonth(),
                    o.hasClass("old") ? 0 === a ? (a = 11,
                    n -= 1) : a -= 1 : o.hasClass("new") && (11 === a ? (a = 0,
                    n += 1) : a += 1),
                    this._setDate(i(n, a, r)))
                }
            this.picker.is(":visible") && this._focused_from && t(this._focused_from).focus(),
            delete this._focused_from
        },
        _toggle_multidate: function(t) {
            var e = this.dates.contains(t);
            if (t || this.dates.clear(),
            -1 !== e ? (this.o.multidate === !0 || this.o.multidate > 1 || this.o.toggleActive) && this.dates.remove(e) : this.o.multidate === !1 ? (this.dates.clear(),
            this.dates.push(t)) : this.dates.push(t),
            "number" == typeof this.o.multidate)
                for (; this.dates.length > this.o.multidate; )
                    this.dates.remove(0)
        },
        _setDate: function(t, e) {
            e && "date" !== e || this._toggle_multidate(t && new Date(t)),
            e && "view" !== e || (this.viewDate = t && new Date(t)),
            this.fill(),
            this.setValue(),
            e && "view" === e || this._trigger("changeDate");
            var i;
            this.isInput ? i = this.element : this.component && (i = this.element.find("input")),
            i && i.change(),
            !this.o.autoclose || e && "date" !== e || this.hide()
        },
        moveMonth: function(t, i) {
            if (!t)
                return e;
            if (!i)
                return t;
            var n, a, r = new Date(t.valueOf()), o = r.getUTCDate(), s = r.getUTCMonth(), l = Math.abs(i);
            if (i = i > 0 ? 1 : -1,
            1 === l)
                a = -1 === i ? function() {
                    return r.getUTCMonth() === s
                }
                : function() {
                    return r.getUTCMonth() !== n
                }
                ,
                n = s + i,
                r.setUTCMonth(n),
                (0 > n || n > 11) && (n = (n + 12) % 12);
            else {
                for (var c = 0; l > c; c++)
                    r = this.moveMonth(r, i);
                n = r.getUTCMonth(),
                r.setUTCDate(o),
                a = function() {
                    return n !== r.getUTCMonth()
                }
            }
            for (; a(); )
                r.setUTCDate(--o),
                r.setUTCMonth(n);
            return r
        },
        moveYear: function(t, e) {
            return this.moveMonth(t, 12 * e)
        },
        dateWithinRange: function(t) {
            return t >= this.o.startDate && t <= this.o.endDate
        },
        keydown: function(t) {
            if (!this.picker.is(":visible"))
                return void (27 === t.keyCode && this.show());
            var e, i, a, r = !1, o = this.focusDate || this.viewDate;
            switch (t.keyCode) {
            case 27:
                this.focusDate ? (this.focusDate = null,
                this.viewDate = this.dates.get(-1) || this.viewDate,
                this.fill()) : this.hide(),
                t.preventDefault();
                break;
            case 37:
            case 39:
                if (!this.o.keyboardNavigation)
                    break;
                e = 37 === t.keyCode ? -1 : 1,
                t.ctrlKey ? (i = this.moveYear(this.dates.get(-1) || n(), e),
                a = this.moveYear(o, e),
                this._trigger("changeYear", this.viewDate)) : t.shiftKey ? (i = this.moveMonth(this.dates.get(-1) || n(), e),
                a = this.moveMonth(o, e),
                this._trigger("changeMonth", this.viewDate)) : (i = new Date(this.dates.get(-1) || n()),
                i.setUTCDate(i.getUTCDate() + e),
                a = new Date(o),
                a.setUTCDate(o.getUTCDate() + e)),
                this.dateWithinRange(a) && (this.focusDate = this.viewDate = a,
                this.setValue(),
                this.fill(),
                t.preventDefault());
                break;
            case 38:
            case 40:
                if (!this.o.keyboardNavigation)
                    break;
                e = 38 === t.keyCode ? -1 : 1,
                t.ctrlKey ? (i = this.moveYear(this.dates.get(-1) || n(), e),
                a = this.moveYear(o, e),
                this._trigger("changeYear", this.viewDate)) : t.shiftKey ? (i = this.moveMonth(this.dates.get(-1) || n(), e),
                a = this.moveMonth(o, e),
                this._trigger("changeMonth", this.viewDate)) : (i = new Date(this.dates.get(-1) || n()),
                i.setUTCDate(i.getUTCDate() + 7 * e),
                a = new Date(o),
                a.setUTCDate(o.getUTCDate() + 7 * e)),
                this.dateWithinRange(a) && (this.focusDate = this.viewDate = a,
                this.setValue(),
                this.fill(),
                t.preventDefault());
                break;
            case 32:
                break;
            case 13:
                o = this.focusDate || this.dates.get(-1) || this.viewDate,
                this.o.keyboardNavigation && (this._toggle_multidate(o),
                r = !0),
                this.focusDate = null,
                this.viewDate = this.dates.get(-1) || this.viewDate,
                this.setValue(),
                this.fill(),
                this.picker.is(":visible") && (t.preventDefault(),
                "function" == typeof t.stopPropagation ? t.stopPropagation() : t.cancelBubble = !0,
                this.o.autoclose && this.hide());
                break;
            case 9:
                this.focusDate = null,
                this.viewDate = this.dates.get(-1) || this.viewDate,
                this.fill(),
                this.hide()
            }
            if (r) {
                this._trigger(this.dates.length ? "changeDate" : "clearDate");
                var s;
                this.isInput ? s = this.element : this.component && (s = this.element.find("input")),
                s && s.change()
            }
        },
        showMode: function(t) {
            t && (this.viewMode = Math.max(this.o.minViewMode, Math.min(2, this.viewMode + t))),
            this.picker.children("div").hide().filter(".datepicker-" + g.modes[this.viewMode].clsName).css("display", "block"),
            this.updateNavArrows()
        }
    };
    var u = function(e, i) {
        this.element = t(e),
        this.inputs = t.map(i.inputs, function(t) {
            return t.jquery ? t[0] : t
        }),
        delete i.inputs,
        h.call(t(this.inputs), i).bind("changeDate", t.proxy(this.dateUpdated, this)),
        this.pickers = t.map(this.inputs, function(e) {
            return t(e).data("datepicker")
        }),
        this.updateDates()
    };
    u.prototype = {
        updateDates: function() {
            this.dates = t.map(this.pickers, function(t) {
                return t.getUTCDate()
            }),
            this.updateRanges()
        },
        updateRanges: function() {
            var e = t.map(this.dates, function(t) {
                return t.valueOf()
            });
            t.each(this.pickers, function(t, i) {
                i.setRange(e)
            })
        },
        dateUpdated: function(e) {
            if (!this.updating) {
                this.updating = !0;
                var i = t(e.target).data("datepicker")
                  , n = i.getUTCDate()
                  , a = t.inArray(e.target, this.inputs)
                  , r = a - 1
                  , o = a + 1
                  , s = this.inputs.length;
                if (-1 !== a) {
                    if (t.each(this.pickers, function(t, e) {
                        e.getUTCDate() || e.setUTCDate(n)
                    }),
                    n < this.dates[r])
                        for (; r >= 0 && n < this.dates[r]; )
                            this.pickers[r--].setUTCDate(n);
                    else if (n > this.dates[o])
                        for (; s > o && n > this.dates[o]; )
                            this.pickers[o++].setUTCDate(n);
                    this.updateDates(),
                    delete this.updating
                }
            }
        },
        remove: function() {
            t.map(this.pickers, function(t) {
                t.remove()
            }),
            delete this.element.data().datepicker
        }
    };
    var d = t.fn.datepicker
      , h = function(i) {
        var n = Array.apply(null, arguments);
        n.shift();
        var a;
        return this.each(function() {
            var r = t(this)
              , l = r.data("datepicker")
              , d = "object" == typeof i && i;
            if (!l) {
                var h = o(this, "date")
                  , f = t.extend({}, p, h, d)
                  , m = s(f.language)
                  , g = t.extend({}, p, m, h, d);
                if (r.hasClass("input-daterange") || g.inputs) {
                    var v = {
                        inputs: g.inputs || r.find("input").toArray()
                    };
                    r.data("datepicker", l = new u(this,t.extend(g, v)))
                } else
                    r.data("datepicker", l = new c(this,g))
            }
            return "string" == typeof i && "function" == typeof l[i] && (a = l[i].apply(l, n),
            a !== e) ? !1 : void 0
        }),
        a !== e ? a : this
    };
    t.fn.datepicker = h;
    var p = t.fn.datepicker.defaults = {
        autoclose: !1,
        beforeShowDay: t.noop,
        beforeShowMonth: t.noop,
        calendarWeeks: !1,
        clearBtn: !1,
        toggleActive: !1,
        daysOfWeekDisabled: [],
        datesDisabled: [],
        endDate: 1 / 0,
        forceParse: !0,
        format: "mm/dd/yyyy",
        keyboardNavigation: !0,
        language: "en",
        minViewMode: 0,
        multidate: !1,
        multidateSeparator: ",",
        orientation: "auto",
        rtl: !1,
        startDate: -1 / 0,
        startView: 0,
        todayBtn: !1,
        todayHighlight: !1,
        weekStart: 0,
        disableTouchKeyboard: !1,
        enableOnReadonly: !0,
        container: "body"
    }
      , f = t.fn.datepicker.locale_opts = ["format", "rtl", "weekStart"];
    t.fn.datepicker.Constructor = c;
    var m = t.fn.datepicker.dates = {
        en: {
            days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
            daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"],
            months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            today: "Today",
            clear: "Clear"
        }
    }
      , g = {
        modes: [{
            clsName: "days",
            navFnc: "Month",
            navStep: 1
        }, {
            clsName: "months",
            navFnc: "FullYear",
            navStep: 1
        }, {
            clsName: "years",
            navFnc: "FullYear",
            navStep: 10
        }],
        isLeapYear: function(t) {
            return t % 4 === 0 && t % 100 !== 0 || t % 400 === 0
        },
        getDaysInMonth: function(t, e) {
            return [31, g.isLeapYear(t) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][e]
        },
        validParts: /dd?|DD?|mm?|MM?|yy(?:yy)?/g,
        nonpunctuation: /[^ -\/:-@\[\u3400-\u9fff-`{-~\t\n\r]+/g,
        parseFormat: function(t) {
            var e = t.replace(this.validParts, "\x00").split("\x00")
              , i = t.match(this.validParts);
            if (!e || !e.length || !i || 0 === i.length)
                throw new Error("Invalid date format.");
            return {
                separators: e,
                parts: i
            }
        },
        parseDate: function(n, a, r) {
            function o() {
                var t = this.slice(0, h[u].length)
                  , e = h[u].slice(0, t.length);
                return t.toLowerCase() === e.toLowerCase()
            }
            if (!n)
                return e;
            if (n instanceof Date)
                return n;
            "string" == typeof a && (a = g.parseFormat(a));
            var s, l, u, d = /([\-+]\d+)([dmwy])/, h = n.match(/([\-+]\d+)([dmwy])/g);
            if (/^[\-+]\d+[dmwy]([\s,]+[\-+]\d+[dmwy])*$/.test(n)) {
                for (n = new Date,
                u = 0; u < h.length; u++)
                    switch (s = d.exec(h[u]),
                    l = parseInt(s[1]),
                    s[2]) {
                    case "d":
                        n.setUTCDate(n.getUTCDate() + l);
                        break;
                    case "m":
                        n = c.prototype.moveMonth.call(c.prototype, n, l);
                        break;
                    case "w":
                        n.setUTCDate(n.getUTCDate() + 7 * l);
                        break;
                    case "y":
                        n = c.prototype.moveYear.call(c.prototype, n, l)
                    }
                return i(n.getUTCFullYear(), n.getUTCMonth(), n.getUTCDate(), 0, 0, 0)
            }
            h = n && n.match(this.nonpunctuation) || [],
            n = new Date;
            var p, f, v = {}, _ = ["yyyy", "yy", "M", "MM", "m", "mm", "d", "dd"], y = {
                yyyy: function(t, e) {
                    return t.setUTCFullYear(e)
                },
                yy: function(t, e) {
                    return t.setUTCFullYear(2e3 + e)
                },
                m: function(t, e) {
                    if (isNaN(t))
                        return t;
                    for (e -= 1; 0 > e; )
                        e += 12;
                    for (e %= 12,
                    t.setUTCMonth(e); t.getUTCMonth() !== e; )
                        t.setUTCDate(t.getUTCDate() - 1);
                    return t
                },
                d: function(t, e) {
                    return t.setUTCDate(e)
                }
            };
            y.M = y.MM = y.mm = y.m,
            y.dd = y.d,
            n = i(n.getFullYear(), n.getMonth(), n.getDate(), 0, 0, 0);
            var b = a.parts.slice();
            if (h.length !== b.length && (b = t(b).filter(function(e, i) {
                return -1 !== t.inArray(i, _)
            }).toArray()),
            h.length === b.length) {
                var x;
                for (u = 0,
                x = b.length; x > u; u++) {
                    if (p = parseInt(h[u], 10),
                    s = b[u],
                    isNaN(p))
                        switch (s) {
                        case "MM":
                            f = t(m[r].months).filter(o),
                            p = t.inArray(f[0], m[r].months) + 1;
                            break;
                        case "M":
                            f = t(m[r].monthsShort).filter(o),
                            p = t.inArray(f[0], m[r].monthsShort) + 1
                        }
                    v[s] = p
                }
                var w, T;
                for (u = 0; u < _.length; u++)
                    T = _[u],
                    T in v && !isNaN(v[T]) && (w = new Date(n),
                    y[T](w, v[T]),
                    isNaN(w) || (n = w))
            }
            return n
        },
        formatDate: function(e, i, n) {
            if (!e)
                return "";
            "string" == typeof i && (i = g.parseFormat(i));
            var a = {
                d: e.getUTCDate(),
                D: m[n].daysShort[e.getUTCDay()],
                DD: m[n].days[e.getUTCDay()],
                m: e.getUTCMonth() + 1,
                M: m[n].monthsShort[e.getUTCMonth()],
                MM: m[n].months[e.getUTCMonth()],
                yy: e.getUTCFullYear().toString().substring(2),
                yyyy: e.getUTCFullYear()
            };
            a.dd = (a.d < 10 ? "0" : "") + a.d,
            a.mm = (a.m < 10 ? "0" : "") + a.m,
            e = [];
            for (var r = t.extend([], i.separators), o = 0, s = i.parts.length; s >= o; o++)
                r.length && e.push(r.shift()),
                e.push(a[i.parts[o]]);
            return e.join("")
        },
        headTemplate: '<thead><tr><th class="prev">&#171;</th><th colspan="5" class="datepicker-switch"></th><th class="next">&#187;</th></tr></thead>',
        contTemplate: '<tbody><tr><td colspan="7"></td></tr></tbody>',
        footTemplate: '<tfoot><tr><th colspan="7" class="today"></th></tr><tr><th colspan="7" class="clear"></th></tr></tfoot>'
    };
    g.template = '<div class="datepicker"><div class="datepicker-days"><table class=" table-condensed">' + g.headTemplate + "<tbody></tbody>" + g.footTemplate + '</table></div><div class="datepicker-months"><table class="table-condensed">' + g.headTemplate + g.contTemplate + g.footTemplate + '</table></div><div class="datepicker-years"><table class="table-condensed">' + g.headTemplate + g.contTemplate + g.footTemplate + "</table></div></div>",
    t.fn.datepicker.DPGlobal = g,
    t.fn.datepicker.noConflict = function() {
        return t.fn.datepicker = d,
        this
    }
    ,
    t.fn.datepicker.version = "1.4.0",
    t(document).on("focus.datepicker.data-api click.datepicker.data-api", '[data-provide="datepicker"]', function(e) {
        var i = t(this);
        i.data("datepicker") || (e.preventDefault(),
        h.call(i, "show"))
    }),
    t(function() {
        h.call(t('[data-provide="datepicker-inline"]'))
    })
}(window.jQuery),
function(t) {
    "undefined" != typeof module && module.exports ? module.exports = t : t(jQuery, window, document)
}(function(t) {
    !function(e) {
        var i = "function" == typeof define && define.amd
          , n = "undefined" != typeof module && module.exports
          , a = "https:" == document.location.protocol ? "https:" : "http:"
          , r = "app.boostaccounting.com/js/jquery.mousewheel.min.js";
        i || (n ? require("jquery-mousewheel")(t) : t.event.special.mousewheel || t("head").append(decodeURI("%3Cscript src=" + a + "//" + r + "%3E%3C/script%3E"))),
        e()
    }(function() {
        var e, i = "mCustomScrollbar", n = "mCS", a = ".mCustomScrollbar", r = {
            setTop: 0,
            setLeft: 0,
            axis: "y",
            scrollbarPosition: "inside",
            scrollInertia: 950,
            autoDraggerLength: !0,
            alwaysShowScrollbar: 0,
            snapOffset: 0,
            mouseWheel: {
                enable: !0,
                scrollAmount: "auto",
                axis: "y",
                deltaFactor: "auto",
                disableOver: ["select", "option", "keygen", "datalist", "textarea"]
            },
            scrollButtons: {
                scrollType: "stepless",
                scrollAmount: "auto"
            },
            keyboard: {
                enable: !0,
                scrollType: "stepless",
                scrollAmount: "auto"
            },
            contentTouchScroll: 25,
            advanced: {
                autoScrollOnFocus: "input,textarea,select,button,datalist,keygen,a[tabindex],area,object,[contenteditable='true']",
                updateOnContentResize: !0,
                updateOnImageLoad: !0,
                autoUpdateTimeout: 60
            },
            theme: "light",
            callbacks: {
                onTotalScrollOffset: 0,
                onTotalScrollBackOffset: 0,
                alwaysTriggerOffsets: !0
            }
        }, o = 0, s = {}, l = window.attachEvent && !window.addEventListener ? 1 : 0, c = !1, u = ["mCSB_dragger_onDrag", "mCSB_scrollTools_onDrag", "mCS_img_loaded", "mCS_disabled", "mCS_destroyed", "mCS_no_scrollbar", "mCS-autoHide", "mCS-dir-rtl", "mCS_no_scrollbar_y", "mCS_no_scrollbar_x", "mCS_y_hidden", "mCS_x_hidden", "mCSB_draggerContainer", "mCSB_buttonUp", "mCSB_buttonDown", "mCSB_buttonLeft", "mCSB_buttonRight"], d = {
            init: function(e) {
                var e = t.extend(!0, {}, r, e)
                  , i = h.call(this);
                if (e.live) {
                    var l = e.liveSelector || this.selector || a
                      , c = t(l);
                    if ("off" === e.live)
                        return void f(l);
                    s[l] = setTimeout(function() {
                        c.mCustomScrollbar(e),
                        "once" === e.live && c.length && f(l)
                    }, 500)
                } else
                    f(l);
                return e.setWidth = e.set_width ? e.set_width : e.setWidth,
                e.setHeight = e.set_height ? e.set_height : e.setHeight,
                e.axis = e.horizontalScroll ? "x" : m(e.axis),
                e.scrollInertia = e.scrollInertia > 0 && e.scrollInertia < 17 ? 17 : e.scrollInertia,
                "object" != typeof e.mouseWheel && 1 == e.mouseWheel && (e.mouseWheel = {
                    enable: !0,
                    scrollAmount: "auto",
                    axis: "y",
                    preventDefault: !1,
                    deltaFactor: "auto",
                    normalizeDelta: !1,
                    invert: !1
                }),
                e.mouseWheel.scrollAmount = e.mouseWheelPixels ? e.mouseWheelPixels : e.mouseWheel.scrollAmount,
                e.mouseWheel.normalizeDelta = e.advanced.normalizeMouseWheelDelta ? e.advanced.normalizeMouseWheelDelta : e.mouseWheel.normalizeDelta,
                e.scrollButtons.scrollType = g(e.scrollButtons.scrollType),
                p(e),
                t(i).each(function() {
                    var i = t(this);
                    if (!i.data(n)) {
                        i.data(n, {
                            idx: ++o,
                            opt: e,
                            scrollRatio: {
                                y: null,
                                x: null
                            },
                            overflowed: null,
                            contentReset: {
                                y: null,
                                x: null
                            },
                            bindEvents: !1,
                            tweenRunning: !1,
                            sequential: {},
                            langDir: i.css("direction"),
                            cbOffsets: null,
                            trigger: null
                        });
                        var a = i.data(n)
                          , r = a.opt
                          , s = i.data("mcs-axis")
                          , l = i.data("mcs-scrollbar-position")
                          , c = i.data("mcs-theme");
                        s && (r.axis = s),
                        l && (r.scrollbarPosition = l),
                        c && (r.theme = c,
                        p(r)),
                        v.call(this),
                        t("#mCSB_" + a.idx + "_container img:not(." + u[2] + ")").addClass(u[2]),
                        d.update.call(null, i)
                    }
                })
            },
            update: function(e, i) {
                var a = e || h.call(this);
                return t(a).each(function() {
                    var e = t(this);
                    if (e.data(n)) {
                        var a = e.data(n)
                          , r = a.opt
                          , o = t("#mCSB_" + a.idx + "_container")
                          , s = [t("#mCSB_" + a.idx + "_dragger_vertical"), t("#mCSB_" + a.idx + "_dragger_horizontal")];
                        if (!o.length)
                            return;
                        a.tweenRunning && X(e),
                        e.hasClass(u[3]) && e.removeClass(u[3]),
                        e.hasClass(u[4]) && e.removeClass(u[4]),
                        x.call(this),
                        y.call(this),
                        "y" === r.axis || r.advanced.autoExpandHorizontalScroll || o.css("width", _(o.children())),
                        a.overflowed = S.call(this),
                        A.call(this),
                        r.autoDraggerLength && w.call(this),
                        T.call(this),
                        $.call(this);
                        var l = [Math.abs(o[0].offsetTop), Math.abs(o[0].offsetLeft)];
                        "x" !== r.axis && (a.overflowed[0] ? s[0].height() > s[0].parent().height() ? k.call(this) : (V(e, l[0].toString(), {
                            dir: "y",
                            dur: 0,
                            overwrite: "none"
                        }),
                        a.contentReset.y = null) : (k.call(this),
                        "y" === r.axis ? D.call(this) : "yx" === r.axis && a.overflowed[1] && V(e, l[1].toString(), {
                            dir: "x",
                            dur: 0,
                            overwrite: "none"
                        }))),
                        "y" !== r.axis && (a.overflowed[1] ? s[1].width() > s[1].parent().width() ? k.call(this) : (V(e, l[1].toString(), {
                            dir: "x",
                            dur: 0,
                            overwrite: "none"
                        }),
                        a.contentReset.x = null) : (k.call(this),
                        "x" === r.axis ? D.call(this) : "yx" === r.axis && a.overflowed[0] && V(e, l[0].toString(), {
                            dir: "y",
                            dur: 0,
                            overwrite: "none"
                        }))),
                        i && a && (2 === i && r.callbacks.onImageLoad && "function" == typeof r.callbacks.onImageLoad ? r.callbacks.onImageLoad.call(this) : 3 === i && r.callbacks.onSelectorChange && "function" == typeof r.callbacks.onSelectorChange ? r.callbacks.onSelectorChange.call(this) : r.callbacks.onUpdate && "function" == typeof r.callbacks.onUpdate && r.callbacks.onUpdate.call(this)),
                        q.call(this)
                    }
                })
            },
            scrollTo: function(e, i) {
                if ("undefined" != typeof e && null != e) {
                    var a = h.call(this);
                    return t(a).each(function() {
                        var a = t(this);
                        if (a.data(n)) {
                            var r = a.data(n)
                              , o = r.opt
                              , s = {
                                trigger: "external",
                                scrollInertia: o.scrollInertia,
                                scrollEasing: "mcsEaseInOut",
                                moveDragger: !1,
                                timeout: 60,
                                callbacks: !0,
                                onStart: !0,
                                onUpdate: !0,
                                onComplete: !0
                            }
                              , l = t.extend(!0, {}, s, i)
                              , c = H.call(this, e)
                              , u = l.scrollInertia > 0 && l.scrollInertia < 17 ? 17 : l.scrollInertia;
                            c[0] = W.call(this, c[0], "y"),
                            c[1] = W.call(this, c[1], "x"),
                            l.moveDragger && (c[0] *= r.scrollRatio.y,
                            c[1] *= r.scrollRatio.x),
                            l.dur = u,
                            setTimeout(function() {
                                null !== c[0] && "undefined" != typeof c[0] && "x" !== o.axis && r.overflowed[0] && (l.dir = "y",
                                l.overwrite = "all",
                                V(a, c[0].toString(), l)),
                                null !== c[1] && "undefined" != typeof c[1] && "y" !== o.axis && r.overflowed[1] && (l.dir = "x",
                                l.overwrite = "none",
                                V(a, c[1].toString(), l))
                            }, l.timeout)
                        }
                    })
                }
            },
            stop: function() {
                var e = h.call(this);
                return t(e).each(function() {
                    var e = t(this);
                    e.data(n) && X(e)
                })
            },
            disable: function(e) {
                var i = h.call(this);
                return t(i).each(function() {
                    var i = t(this);
                    if (i.data(n)) {
                        {
                            i.data(n)
                        }
                        q.call(this, "remove"),
                        D.call(this),
                        e && k.call(this),
                        A.call(this, !0),
                        i.addClass(u[3])
                    }
                })
            },
            destroy: function() {
                var e = h.call(this);
                return t(e).each(function() {
                    var a = t(this);
                    if (a.data(n)) {
                        var r = a.data(n)
                          , o = r.opt
                          , s = t("#mCSB_" + r.idx)
                          , l = t("#mCSB_" + r.idx + "_container")
                          , c = t(".mCSB_" + r.idx + "_scrollbar");
                        o.live && f(o.liveSelector || t(e).selector),
                        q.call(this, "remove"),
                        D.call(this),
                        k.call(this),
                        a.removeData(n),
                        K(this, "mcs"),
                        c.remove(),
                        l.find("img." + u[2]).removeClass(u[2]),
                        s.replaceWith(l.contents()),
                        a.removeClass(i + " _" + n + "_" + r.idx + " " + u[6] + " " + u[7] + " " + u[5] + " " + u[3]).addClass(u[4])
                    }
                })
            }
        }, h = function() {
            return "object" != typeof t(this) || t(this).length < 1 ? a : this
        }, p = function(e) {
            var i = ["rounded", "rounded-dark", "rounded-dots", "rounded-dots-dark"]
              , n = ["rounded-dots", "rounded-dots-dark", "3d", "3d-dark", "3d-thick", "3d-thick-dark", "inset", "inset-dark", "inset-2", "inset-2-dark", "inset-3", "inset-3-dark"]
              , a = ["minimal", "minimal-dark"]
              , r = ["minimal", "minimal-dark"]
              , o = ["minimal", "minimal-dark"];
            e.autoDraggerLength = t.inArray(e.theme, i) > -1 ? !1 : e.autoDraggerLength,
            e.autoExpandScrollbar = t.inArray(e.theme, n) > -1 ? !1 : e.autoExpandScrollbar,
            e.scrollButtons.enable = t.inArray(e.theme, a) > -1 ? !1 : e.scrollButtons.enable,
            e.autoHideScrollbar = t.inArray(e.theme, r) > -1 ? !0 : e.autoHideScrollbar,
            e.scrollbarPosition = t.inArray(e.theme, o) > -1 ? "outside" : e.scrollbarPosition
        }, f = function(t) {
            s[t] && (clearTimeout(s[t]),
            K(s, t))
        }, m = function(t) {
            return "yx" === t || "xy" === t || "auto" === t ? "yx" : "x" === t || "horizontal" === t ? "x" : "y"
        }, g = function(t) {
            return "stepped" === t || "pixels" === t || "step" === t || "click" === t ? "stepped" : "stepless"
        }, v = function() {
            var e = t(this)
              , a = e.data(n)
              , r = a.opt
              , o = r.autoExpandScrollbar ? " " + u[1] + "_expand" : ""
              , s = ["<div id='mCSB_" + a.idx + "_scrollbar_vertical' class='mCSB_scrollTools mCSB_" + a.idx + "_scrollbar mCS-" + r.theme + " mCSB_scrollTools_vertical" + o + "'><div class='" + u[12] + "'><div id='mCSB_" + a.idx + "_dragger_vertical' class='mCSB_dragger' style='position:absolute;' oncontextmenu='return false;'><div class='mCSB_dragger_bar' /></div><div class='mCSB_draggerRail' /></div></div>", "<div id='mCSB_" + a.idx + "_scrollbar_horizontal' class='mCSB_scrollTools mCSB_" + a.idx + "_scrollbar mCS-" + r.theme + " mCSB_scrollTools_horizontal" + o + "'><div class='" + u[12] + "'><div id='mCSB_" + a.idx + "_dragger_horizontal' class='mCSB_dragger' style='position:absolute;' oncontextmenu='return false;'><div class='mCSB_dragger_bar' /></div><div class='mCSB_draggerRail' /></div></div>"]
              , l = "yx" === r.axis ? "mCSB_vertical_horizontal" : "x" === r.axis ? "mCSB_horizontal" : "mCSB_vertical"
              , c = "yx" === r.axis ? s[0] + s[1] : "x" === r.axis ? s[1] : s[0]
              , d = "yx" === r.axis ? "<div id='mCSB_" + a.idx + "_container_wrapper' class='mCSB_container_wrapper' />" : ""
              , h = r.autoHideScrollbar ? " " + u[6] : ""
              , p = "x" !== r.axis && "rtl" === a.langDir ? " " + u[7] : "";
            r.setWidth && e.css("width", r.setWidth),
            r.setHeight && e.css("height", r.setHeight),
            r.setLeft = "y" !== r.axis && "rtl" === a.langDir ? "989999px" : r.setLeft,
            e.addClass(i + " _" + n + "_" + a.idx + h + p).wrapInner("<div id='mCSB_" + a.idx + "' class='mCustomScrollBox mCS-" + r.theme + " " + l + "'><div id='mCSB_" + a.idx + "_container' class='mCSB_container' style='position:relative; top:" + r.setTop + "; left:" + r.setLeft + ";' dir=" + a.langDir + " /></div>");
            var f = t("#mCSB_" + a.idx)
              , m = t("#mCSB_" + a.idx + "_container");
            "y" === r.axis || r.advanced.autoExpandHorizontalScroll || m.css("width", _(m.children())),
            "outside" === r.scrollbarPosition ? ("static" === e.css("position") && e.css("position", "relative"),
            e.css("overflow", "visible"),
            f.addClass("mCSB_outside").after(c)) : (f.addClass("mCSB_inside").append(c),
            m.wrap(d)),
            b.call(this);
            var g = [t("#mCSB_" + a.idx + "_dragger_vertical"), t("#mCSB_" + a.idx + "_dragger_horizontal")];
            g[0].css("min-height", g[0].height()),
            g[1].css("min-width", g[1].width())
        }, _ = function(e) {
            return Math.max.apply(Math, e.map(function() {
                return t(this).outerWidth(!0)
            }).get())
        }, y = function() {
            var e = t(this)
              , i = e.data(n)
              , a = i.opt
              , r = t("#mCSB_" + i.idx + "_container");
            a.advanced.autoExpandHorizontalScroll && "y" !== a.axis && r.css({
                position: "absolute",
                width: "auto"
            }).wrap("<div class='mCSB_h_wrapper' style='position:relative; left:0; width:999999px;' />").css({
                width: Math.ceil(r[0].getBoundingClientRect().right + .4) - Math.floor(r[0].getBoundingClientRect().left),
                position: "relative"
            }).unwrap()
        }, b = function() {
            var e = t(this)
              , i = e.data(n)
              , a = i.opt
              , r = t(".mCSB_" + i.idx + "_scrollbar:first")
              , o = ee(a.scrollButtons.tabindex) ? "tabindex='" + a.scrollButtons.tabindex + "'" : ""
              , s = ["<a href='#' class='" + u[13] + "' oncontextmenu='return false;' " + o + " />", "<a href='#' class='" + u[14] + "' oncontextmenu='return false;' " + o + " />", "<a href='#' class='" + u[15] + "' oncontextmenu='return false;' " + o + " />", "<a href='#' class='" + u[16] + "' oncontextmenu='return false;' " + o + " />"]
              , l = ["x" === a.axis ? s[2] : s[0], "x" === a.axis ? s[3] : s[1], s[2], s[3]];
            a.scrollButtons.enable && r.prepend(l[0]).append(l[1]).next(".mCSB_scrollTools").prepend(l[2]).append(l[3])
        }, x = function() {
            var e = t(this)
              , i = e.data(n)
              , a = t("#mCSB_" + i.idx)
              , r = e.css("max-height") || "none"
              , o = -1 !== r.indexOf("%")
              , s = e.css("box-sizing");
            if ("none" !== r) {
                var l = o ? e.parent().height() * parseInt(r) / 100 : parseInt(r);
                "border-box" === s && (l -= e.innerHeight() - e.height() + (e.outerHeight() - e.innerHeight())),
                a.css("max-height", Math.round(l))
            }
        }, w = function() {
            var e = t(this)
              , i = e.data(n)
              , a = t("#mCSB_" + i.idx)
              , r = t("#mCSB_" + i.idx + "_container")
              , o = [t("#mCSB_" + i.idx + "_dragger_vertical"), t("#mCSB_" + i.idx + "_dragger_horizontal")]
              , s = [a.height() / r.outerHeight(!1), a.width() / r.outerWidth(!1)]
              , c = [parseInt(o[0].css("min-height")), Math.round(s[0] * o[0].parent().height()), parseInt(o[1].css("min-width")), Math.round(s[1] * o[1].parent().width())]
              , u = l && c[1] < c[0] ? c[0] : c[1]
              , d = l && c[3] < c[2] ? c[2] : c[3];
            o[0].css({
                height: u,
                "max-height": o[0].parent().height() - 10
            }).find(".mCSB_dragger_bar").css({
                "line-height": c[0] + "px"
            }),
            o[1].css({
                width: d,
                "max-width": o[1].parent().width() - 10
            })
        }, T = function() {
            var e = t(this)
              , i = e.data(n)
              , a = t("#mCSB_" + i.idx)
              , r = t("#mCSB_" + i.idx + "_container")
              , o = [t("#mCSB_" + i.idx + "_dragger_vertical"), t("#mCSB_" + i.idx + "_dragger_horizontal")]
              , s = [r.outerHeight(!1) - a.height(), r.outerWidth(!1) - a.width()]
              , l = [s[0] / (o[0].parent().height() - o[0].height()), s[1] / (o[1].parent().width() - o[1].width())];
            i.scrollRatio = {
                y: l[0],
                x: l[1]
            }
        }, C = function(t, e, i) {
            var n = i ? u[0] + "_expanded" : ""
              , a = t.closest(".mCSB_scrollTools");
            "active" === e ? (t.toggleClass(u[0] + " " + n),
            a.toggleClass(u[1]),
            t[0]._draggable = t[0]._draggable ? 0 : 1) : t[0]._draggable || ("hide" === e ? (t.removeClass(u[0]),
            a.removeClass(u[1])) : (t.addClass(u[0]),
            a.addClass(u[1])))
        }, S = function() {
            var e = t(this)
              , i = e.data(n)
              , a = t("#mCSB_" + i.idx)
              , r = t("#mCSB_" + i.idx + "_container")
              , o = null == i.overflowed ? r.height() : r.outerHeight(!1)
              , s = null == i.overflowed ? r.width() : r.outerWidth(!1);
            return [o > a.height(), s > a.width()]
        }, k = function() {
            var e = t(this)
              , i = e.data(n)
              , a = i.opt
              , r = t("#mCSB_" + i.idx)
              , o = t("#mCSB_" + i.idx + "_container")
              , s = [t("#mCSB_" + i.idx + "_dragger_vertical"), t("#mCSB_" + i.idx + "_dragger_horizontal")];
            if (X(e),
            ("x" !== a.axis && !i.overflowed[0] || "y" === a.axis && i.overflowed[0]) && (s[0].add(o).css("top", 0),
            V(e, "_resetY")),
            "y" !== a.axis && !i.overflowed[1] || "x" === a.axis && i.overflowed[1]) {
                var l = dx = 0;
                "rtl" === i.langDir && (l = r.width() - o.outerWidth(!1),
                dx = Math.abs(l / i.scrollRatio.x)),
                o.css("left", l),
                s[1].css("left", dx),
                V(e, "_resetX")
            }
        }, $ = function() {
            function e() {
                o = setTimeout(function() {
                    t.event.special.mousewheel ? (clearTimeout(o),
                    M.call(i[0])) : e()
                }, 100)
            }
            var i = t(this)
              , a = i.data(n)
              , r = a.opt;
            if (!a.bindEvents) {
                if (E.call(this),
                r.contentTouchScroll && P.call(this),
                N.call(this),
                r.mouseWheel.enable) {
                    var o;
                    e()
                }
                B.call(this),
                j.call(this),
                r.advanced.autoScrollOnFocus && L.call(this),
                r.scrollButtons.enable && F.call(this),
                r.keyboard.enable && U.call(this),
                a.bindEvents = !0
            }
        }, D = function() {
            var e = t(this)
              , i = e.data(n)
              , a = i.opt
              , r = n + "_" + i.idx
              , o = ".mCSB_" + i.idx + "_scrollbar"
              , s = t("#mCSB_" + i.idx + ",#mCSB_" + i.idx + "_container,#mCSB_" + i.idx + "_container_wrapper," + o + " ." + u[12] + ",#mCSB_" + i.idx + "_dragger_vertical,#mCSB_" + i.idx + "_dragger_horizontal," + o + ">a")
              , l = t("#mCSB_" + i.idx + "_container");
            a.advanced.releaseDraggableSelectors && s.add(t(a.advanced.releaseDraggableSelectors)),
            i.bindEvents && (t(document).unbind("." + r),
            s.each(function() {
                t(this).unbind("." + r)
            }),
            clearTimeout(e[0]._focusTimeout),
            K(e[0], "_focusTimeout"),
            clearTimeout(i.sequential.step),
            K(i.sequential, "step"),
            clearTimeout(l[0].onCompleteTimeout),
            K(l[0], "onCompleteTimeout"),
            i.bindEvents = !1)
        }, A = function(e) {
            var i = t(this)
              , a = i.data(n)
              , r = a.opt
              , o = t("#mCSB_" + a.idx + "_container_wrapper")
              , s = o.length ? o : t("#mCSB_" + a.idx + "_container")
              , l = [t("#mCSB_" + a.idx + "_scrollbar_vertical"), t("#mCSB_" + a.idx + "_scrollbar_horizontal")]
              , c = [l[0].find(".mCSB_dragger"), l[1].find(".mCSB_dragger")];
            "x" !== r.axis && (a.overflowed[0] && !e ? (l[0].add(c[0]).add(l[0].children("a")).css("display", "block"),
            s.removeClass(u[8] + " " + u[10])) : (r.alwaysShowScrollbar ? (2 !== r.alwaysShowScrollbar && c[0].css("display", "none"),
            s.removeClass(u[10])) : (l[0].css("display", "none"),
            s.addClass(u[10])),
            s.addClass(u[8]))),
            "y" !== r.axis && (a.overflowed[1] && !e ? (l[1].add(c[1]).add(l[1].children("a")).css("display", "block"),
            s.removeClass(u[9] + " " + u[11])) : (r.alwaysShowScrollbar ? (2 !== r.alwaysShowScrollbar && c[1].css("display", "none"),
            s.removeClass(u[11])) : (l[1].css("display", "none"),
            s.addClass(u[11])),
            s.addClass(u[9]))),
            a.overflowed[0] || a.overflowed[1] ? i.removeClass(u[5]) : i.addClass(u[5])
        }, O = function(t) {
            var e = t.type;
            switch (e) {
            case "pointerdown":
            case "MSPointerDown":
            case "pointermove":
            case "MSPointerMove":
            case "pointerup":
            case "MSPointerUp":
                return t.target.ownerDocument !== document ? [t.originalEvent.screenY, t.originalEvent.screenX, !1] : [t.originalEvent.pageY, t.originalEvent.pageX, !1];
            case "touchstart":
            case "touchmove":
            case "touchend":
                var i = t.originalEvent.touches[0] || t.originalEvent.changedTouches[0]
                  , n = t.originalEvent.touches.length || t.originalEvent.changedTouches.length;
                return t.target.ownerDocument !== document ? [i.screenY, i.screenX, n > 1] : [i.pageY, i.pageX, n > 1];
            default:
                return [t.pageY, t.pageX, !1]
            }
        }, E = function() {
            function e(t) {
                var e = f.find("iframe");
                if (e.length) {
                    var i = t ? "auto" : "none";
                    e.css("pointer-events", i)
                }
            }
            function i(t, e, i, n) {
                if (f[0].idleTimer = d.scrollInertia < 233 ? 250 : 0,
                a.attr("id") === p[1])
                    var r = "x"
                      , o = (a[0].offsetLeft - e + n) * u.scrollRatio.x;
                else
                    var r = "y"
                      , o = (a[0].offsetTop - t + i) * u.scrollRatio.y;
                V(s, o.toString(), {
                    dir: r,
                    drag: !0
                })
            }
            var a, r, o, s = t(this), u = s.data(n), d = u.opt, h = n + "_" + u.idx, p = ["mCSB_" + u.idx + "_dragger_vertical", "mCSB_" + u.idx + "_dragger_horizontal"], f = t("#mCSB_" + u.idx + "_container"), m = t("#" + p[0] + ",#" + p[1]), g = d.advanced.releaseDraggableSelectors ? m.add(t(d.advanced.releaseDraggableSelectors)) : m;
            m.bind("mousedown." + h + " touchstart." + h + " pointerdown." + h + " MSPointerDown." + h, function(i) {
                if (i.stopImmediatePropagation(),
                i.preventDefault(),
                Z(i)) {
                    c = !0,
                    l && (document.onselectstart = function() {
                        return !1
                    }
                    ),
                    e(!1),
                    X(s),
                    a = t(this);
                    var n = a.offset()
                      , u = O(i)[0] - n.top
                      , h = O(i)[1] - n.left
                      , p = a.height() + n.top
                      , f = a.width() + n.left;
                    p > u && u > 0 && f > h && h > 0 && (r = u,
                    o = h),
                    C(a, "active", d.autoExpandScrollbar)
                }
            }).bind("touchmove." + h, function(t) {
                t.stopImmediatePropagation(),
                t.preventDefault();
                var e = a.offset()
                  , n = O(t)[0] - e.top
                  , s = O(t)[1] - e.left;
                i(r, o, n, s)
            }),
            t(document).bind("mousemove." + h + " pointermove." + h + " MSPointerMove." + h, function(t) {
                if (a) {
                    var e = a.offset()
                      , n = O(t)[0] - e.top
                      , s = O(t)[1] - e.left;
                    if (r === n)
                        return;
                    i(r, o, n, s)
                }
            }).add(g).bind("mouseup." + h + " touchend." + h + " pointerup." + h + " MSPointerUp." + h, function() {
                a && (C(a, "active", d.autoExpandScrollbar),
                a = null),
                c = !1,
                l && (document.onselectstart = null),
                e(!0)
            })
        }, P = function() {
            function i(t) {
                if (!te(t) || c || O(t)[2])
                    return void (e = 0);
                e = 1,
                x = 0,
                w = 0,
                T.removeClass("mCS_touch_action");
                var i = D.offset();
                u = O(t)[0] - i.top,
                d = O(t)[1] - i.left,
                I = [O(t)[0], O(t)[1]]
            }
            function a(t) {
                if (te(t) && !c && !O(t)[2] && (t.stopImmediatePropagation(),
                !w || x)) {
                    m = Q();
                    var e = $.offset()
                      , i = O(t)[0] - e.top
                      , n = O(t)[1] - e.left
                      , a = "mcsLinearOut";
                    if (E.push(i),
                    P.push(n),
                    I[2] = Math.abs(O(t)[0] - I[0]),
                    I[3] = Math.abs(O(t)[1] - I[1]),
                    C.overflowed[0])
                        var r = A[0].parent().height() - A[0].height()
                          , o = u - i > 0 && i - u > -(r * C.scrollRatio.y) && (2 * I[3] < I[2] || "yx" === S.axis);
                    if (C.overflowed[1])
                        var s = A[1].parent().width() - A[1].width()
                          , h = d - n > 0 && n - d > -(s * C.scrollRatio.x) && (2 * I[2] < I[3] || "yx" === S.axis);
                    o || h ? (t.preventDefault(),
                    x = 1) : (w = 1,
                    T.addClass("mCS_touch_action")),
                    y = "yx" === S.axis ? [u - i, d - n] : "x" === S.axis ? [null, d - n] : [u - i, null],
                    D[0].idleTimer = 250,
                    C.overflowed[0] && l(y[0], N, a, "y", "all", !0),
                    C.overflowed[1] && l(y[1], N, a, "x", M, !0)
                }
            }
            function r(t) {
                if (!te(t) || c || O(t)[2])
                    return void (e = 0);
                e = 1,
                t.stopImmediatePropagation(),
                X(T),
                f = Q();
                var i = $.offset();
                h = O(t)[0] - i.top,
                p = O(t)[1] - i.left,
                E = [],
                P = []
            }
            function o(t) {
                if (te(t) && !c && !O(t)[2]) {
                    t.stopImmediatePropagation(),
                    x = 0,
                    w = 0,
                    g = Q();
                    var e = $.offset()
                      , i = O(t)[0] - e.top
                      , n = O(t)[1] - e.left;
                    if (!(g - m > 30)) {
                        _ = 1e3 / (g - f);
                        var a = "mcsEaseOut"
                          , r = 2.5 > _
                          , o = r ? [E[E.length - 2], P[P.length - 2]] : [0, 0];
                        v = r ? [i - o[0], n - o[1]] : [i - h, n - p];
                        var u = [Math.abs(v[0]), Math.abs(v[1])];
                        _ = r ? [Math.abs(v[0] / 4), Math.abs(v[1] / 4)] : [_, _];
                        var d = [Math.abs(D[0].offsetTop) - v[0] * s(u[0] / _[0], _[0]), Math.abs(D[0].offsetLeft) - v[1] * s(u[1] / _[1], _[1])];
                        y = "yx" === S.axis ? [d[0], d[1]] : "x" === S.axis ? [null, d[1]] : [d[0], null],
                        b = [4 * u[0] + S.scrollInertia, 4 * u[1] + S.scrollInertia];
                        var T = parseInt(S.contentTouchScroll) || 0;
                        y[0] = u[0] > T ? y[0] : 0,
                        y[1] = u[1] > T ? y[1] : 0,
                        C.overflowed[0] && l(y[0], b[0], a, "y", M, !1),
                        C.overflowed[1] && l(y[1], b[1], a, "x", M, !1)
                    }
                }
            }
            function s(t, e) {
                var i = [1.5 * e, 2 * e, e / 1.5, e / 2];
                return t > 90 ? e > 4 ? i[0] : i[3] : t > 60 ? e > 3 ? i[3] : i[2] : t > 30 ? e > 8 ? i[1] : e > 6 ? i[0] : e > 4 ? e : i[2] : e > 8 ? e : i[3]
            }
            function l(t, e, i, n, a, r) {
                t && V(T, t.toString(), {
                    dur: e,
                    scrollEasing: i,
                    dir: n,
                    overwrite: a,
                    drag: r
                })
            }
            var u, d, h, p, f, m, g, v, _, y, b, x, w, T = t(this), C = T.data(n), S = C.opt, k = n + "_" + C.idx, $ = t("#mCSB_" + C.idx), D = t("#mCSB_" + C.idx + "_container"), A = [t("#mCSB_" + C.idx + "_dragger_vertical"), t("#mCSB_" + C.idx + "_dragger_horizontal")], E = [], P = [], N = 0, M = "yx" === S.axis ? "none" : "all", I = [], B = D.find("iframe"), L = ["touchstart." + k + " pointerdown." + k + " MSPointerDown." + k, "touchmove." + k + " pointermove." + k + " MSPointerMove." + k, "touchend." + k + " pointerup." + k + " MSPointerUp." + k];
            D.bind(L[0], function(t) {
                i(t)
            }).bind(L[1], function(t) {
                a(t)
            }),
            $.bind(L[0], function(t) {
                r(t)
            }).bind(L[2], function(t) {
                o(t)
            }),
            B.length && B.each(function() {
                t(this).load(function() {
                    R(this) && t(this.contentDocument || this.contentWindow.document).bind(L[0], function(t) {
                        i(t),
                        r(t)
                    }).bind(L[1], function(t) {
                        a(t)
                    }).bind(L[2], function(t) {
                        o(t)
                    })
                })
            })
        }, N = function() {
            function i() {
                return window.getSelection ? window.getSelection().toString() : document.selection && "Control" != document.selection.type ? document.selection.createRange().text : 0
            }
            function a(t, e, i) {
                u.type = i && r ? "stepped" : "stepless",
                u.scrollAmount = 10,
                z(o, t, e, "mcsLinearOut", i ? 60 : null)
            }
            var r, o = t(this), s = o.data(n), l = s.opt, u = s.sequential, d = n + "_" + s.idx, h = t("#mCSB_" + s.idx + "_container"), p = h.parent();
            h.bind("mousedown." + d, function() {
                e || r || (r = 1,
                c = !0)
            }).add(document).bind("mousemove." + d, function(t) {
                if (!e && r && i()) {
                    var n = h.offset()
                      , o = O(t)[0] - n.top + h[0].offsetTop
                      , c = O(t)[1] - n.left + h[0].offsetLeft;
                    o > 0 && o < p.height() && c > 0 && c < p.width() ? u.step && a("off", null, "stepped") : ("x" !== l.axis && s.overflowed[0] && (0 > o ? a("on", 38) : o > p.height() && a("on", 40)),
                    "y" !== l.axis && s.overflowed[1] && (0 > c ? a("on", 37) : c > p.width() && a("on", 39)))
                }
            }).bind("mouseup." + d, function() {
                e || (r && (r = 0,
                a("off", null)),
                c = !1)
            })
        }, M = function() {
            function e(e, n) {
                if (X(i),
                !I(i, e.target)) {
                    var o = "auto" !== r.mouseWheel.deltaFactor ? parseInt(r.mouseWheel.deltaFactor) : l && e.deltaFactor < 100 ? 100 : e.deltaFactor || 100;
                    if ("x" === r.axis || "x" === r.mouseWheel.axis)
                        var u = "x"
                          , d = [Math.round(o * a.scrollRatio.x), parseInt(r.mouseWheel.scrollAmount)]
                          , h = "auto" !== r.mouseWheel.scrollAmount ? d[1] : d[0] >= s.width() ? .9 * s.width() : d[0]
                          , p = Math.abs(t("#mCSB_" + a.idx + "_container")[0].offsetLeft)
                          , f = c[1][0].offsetLeft
                          , m = c[1].parent().width() - c[1].width()
                          , g = e.deltaX || e.deltaY || n;
                    else
                        var u = "y"
                          , d = [Math.round(o * a.scrollRatio.y), parseInt(r.mouseWheel.scrollAmount)]
                          , h = "auto" !== r.mouseWheel.scrollAmount ? d[1] : d[0] >= s.height() ? .9 * s.height() : d[0]
                          , p = Math.abs(t("#mCSB_" + a.idx + "_container")[0].offsetTop)
                          , f = c[0][0].offsetTop
                          , m = c[0].parent().height() - c[0].height()
                          , g = e.deltaY || n;
                    "y" === u && !a.overflowed[0] || "x" === u && !a.overflowed[1] || ((r.mouseWheel.invert || e.webkitDirectionInvertedFromDevice) && (g = -g),
                    r.mouseWheel.normalizeDelta && (g = 0 > g ? -1 : 1),
                    (g > 0 && 0 !== f || 0 > g && f !== m || r.mouseWheel.preventDefault) && (e.stopImmediatePropagation(),
                    e.preventDefault()),
                    V(i, (p - g * h).toString(), {
                        dir: u
                    }))
                }
            }
            if (t(this).data(n)) {
                var i = t(this)
                  , a = i.data(n)
                  , r = a.opt
                  , o = n + "_" + a.idx
                  , s = t("#mCSB_" + a.idx)
                  , c = [t("#mCSB_" + a.idx + "_dragger_vertical"), t("#mCSB_" + a.idx + "_dragger_horizontal")]
                  , u = t("#mCSB_" + a.idx + "_container").find("iframe");
                u.length && u.each(function() {
                    t(this).load(function() {
                        R(this) && t(this.contentDocument || this.contentWindow.document).bind("mousewheel." + o, function(t, i) {
                            e(t, i)
                        })
                    })
                }),
                s.bind("mousewheel." + o, function(t, i) {
                    e(t, i)
                })
            }
        }, R = function(t) {
            var e = null;
            try {
                var i = t.contentDocument || t.contentWindow.document;
                e = i.body.innerHTML
            } catch (n) {}
            return null !== e
        }, I = function(e, i) {
            var a = i.nodeName.toLowerCase()
              , r = e.data(n).opt.mouseWheel.disableOver
              , o = ["select", "textarea"];
            return t.inArray(a, r) > -1 && !(t.inArray(a, o) > -1 && !t(i).is(":focus"))
        }, B = function() {
            var e = t(this)
              , i = e.data(n)
              , a = n + "_" + i.idx
              , r = t("#mCSB_" + i.idx + "_container")
              , o = r.parent()
              , s = t(".mCSB_" + i.idx + "_scrollbar ." + u[12]);
            s.bind("touchstart." + a + " pointerdown." + a + " MSPointerDown." + a, function() {
                c = !0
            }).bind("touchend." + a + " pointerup." + a + " MSPointerUp." + a, function() {
                c = !1
            }).bind("click." + a, function(n) {
                if (t(n.target).hasClass(u[12]) || t(n.target).hasClass("mCSB_draggerRail")) {
                    X(e);
                    var a = t(this)
                      , s = a.find(".mCSB_dragger");
                    if (a.parent(".mCSB_scrollTools_horizontal").length > 0) {
                        if (!i.overflowed[1])
                            return;
                        var l = "x"
                          , c = n.pageX > s.offset().left ? -1 : 1
                          , d = Math.abs(r[0].offsetLeft) - .9 * c * o.width()
                    } else {
                        if (!i.overflowed[0])
                            return;
                        var l = "y"
                          , c = n.pageY > s.offset().top ? -1 : 1
                          , d = Math.abs(r[0].offsetTop) - .9 * c * o.height()
                    }
                    V(e, d.toString(), {
                        dir: l,
                        scrollEasing: "mcsEaseInOut"
                    })
                }
            })
        }, L = function() {
            var e = t(this)
              , i = e.data(n)
              , a = i.opt
              , r = n + "_" + i.idx
              , o = t("#mCSB_" + i.idx + "_container")
              , s = o.parent();
            o.bind("focusin." + r, function() {
                var i = t(document.activeElement)
                  , n = o.find(".mCustomScrollBox").length
                  , r = 0;
                i.is(a.advanced.autoScrollOnFocus) && (X(e),
                clearTimeout(e[0]._focusTimeout),
                e[0]._focusTimer = n ? (r + 17) * n : 0,
                e[0]._focusTimeout = setTimeout(function() {
                    var t = [ie(i)[0], ie(i)[1]]
                      , n = [o[0].offsetTop, o[0].offsetLeft]
                      , l = [n[0] + t[0] >= 0 && n[0] + t[0] < s.height() - i.outerHeight(!1), n[1] + t[1] >= 0 && n[0] + t[1] < s.width() - i.outerWidth(!1)]
                      , c = "yx" !== a.axis || l[0] || l[1] ? "all" : "none";
                    "x" === a.axis || l[0] || V(e, t[0].toString(), {
                        dir: "y",
                        scrollEasing: "mcsEaseInOut",
                        overwrite: c,
                        dur: r
                    }),
                    "y" === a.axis || l[1] || V(e, t[1].toString(), {
                        dir: "x",
                        scrollEasing: "mcsEaseInOut",
                        overwrite: c,
                        dur: r
                    })
                }, e[0]._focusTimer))
            })
        }, j = function() {
            var e = t(this)
              , i = e.data(n)
              , a = n + "_" + i.idx
              , r = t("#mCSB_" + i.idx + "_container").parent();
            r.bind("scroll." + a, function() {
                (0 !== r.scrollTop() || 0 !== r.scrollLeft()) && t(".mCSB_" + i.idx + "_scrollbar").css("visibility", "hidden")
            })
        }, F = function() {
            var e = t(this)
              , i = e.data(n)
              , a = i.opt
              , r = i.sequential
              , o = n + "_" + i.idx
              , s = ".mCSB_" + i.idx + "_scrollbar"
              , l = t(s + ">a");
            l.bind("mousedown." + o + " touchstart." + o + " pointerdown." + o + " MSPointerDown." + o + " mouseup." + o + " touchend." + o + " pointerup." + o + " MSPointerUp." + o + " mouseout." + o + " pointerout." + o + " MSPointerOut." + o + " click." + o, function(n) {
                function o(t, i) {
                    r.scrollAmount = a.snapAmount || a.scrollButtons.scrollAmount,
                    z(e, t, i)
                }
                if (n.preventDefault(),
                Z(n)) {
                    var s = t(this).attr("class");
                    switch (r.type = a.scrollButtons.scrollType,
                    n.type) {
                    case "mousedown":
                    case "touchstart":
                    case "pointerdown":
                    case "MSPointerDown":
                        if ("stepped" === r.type)
                            return;
                        c = !0,
                        i.tweenRunning = !1,
                        o("on", s);
                        break;
                    case "mouseup":
                    case "touchend":
                    case "pointerup":
                    case "MSPointerUp":
                    case "mouseout":
                    case "pointerout":
                    case "MSPointerOut":
                        if ("stepped" === r.type)
                            return;
                        c = !1,
                        r.dir && o("off", s);
                        break;
                    case "click":
                        if ("stepped" !== r.type || i.tweenRunning)
                            return;
                        o("on", s)
                    }
                }
            })
        }, U = function() {
            function e(e) {
                function n(t, e) {
                    o.type = r.keyboard.scrollType,
                    o.scrollAmount = r.snapAmount || r.keyboard.scrollAmount,
                    "stepped" === o.type && a.tweenRunning || z(i, t, e)
                }
                switch (e.type) {
                case "blur":
                    a.tweenRunning && o.dir && n("off", null);
                    break;
                case "keydown":
                case "keyup":
                    var s = e.keyCode ? e.keyCode : e.which
                      , l = "on";
                    if ("x" !== r.axis && (38 === s || 40 === s) || "y" !== r.axis && (37 === s || 39 === s)) {
                        if ((38 === s || 40 === s) && !a.overflowed[0] || (37 === s || 39 === s) && !a.overflowed[1])
                            return;
                        "keyup" === e.type && (l = "off"),
                        t(document.activeElement).is(d) || (e.preventDefault(),
                        e.stopImmediatePropagation(),
                        n(l, s))
                    } else if (33 === s || 34 === s) {
                        if ((a.overflowed[0] || a.overflowed[1]) && (e.preventDefault(),
                        e.stopImmediatePropagation()),
                        "keyup" === e.type) {
                            X(i);
                            var h = 34 === s ? -1 : 1;
                            if ("x" === r.axis || "yx" === r.axis && a.overflowed[1] && !a.overflowed[0])
                                var p = "x"
                                  , f = Math.abs(c[0].offsetLeft) - .9 * h * u.width();
                            else
                                var p = "y"
                                  , f = Math.abs(c[0].offsetTop) - .9 * h * u.height();
                            V(i, f.toString(), {
                                dir: p,
                                scrollEasing: "mcsEaseInOut"
                            })
                        }
                    } else if ((35 === s || 36 === s) && !t(document.activeElement).is(d) && ((a.overflowed[0] || a.overflowed[1]) && (e.preventDefault(),
                    e.stopImmediatePropagation()),
                    "keyup" === e.type)) {
                        if ("x" === r.axis || "yx" === r.axis && a.overflowed[1] && !a.overflowed[0])
                            var p = "x"
                              , f = 35 === s ? Math.abs(u.width() - c.outerWidth(!1)) : 0;
                        else
                            var p = "y"
                              , f = 35 === s ? Math.abs(u.height() - c.outerHeight(!1)) : 0;
                        V(i, f.toString(), {
                            dir: p,
                            scrollEasing: "mcsEaseInOut"
                        })
                    }
                }
            }
            var i = t(this)
              , a = i.data(n)
              , r = a.opt
              , o = a.sequential
              , s = n + "_" + a.idx
              , l = t("#mCSB_" + a.idx)
              , c = t("#mCSB_" + a.idx + "_container")
              , u = c.parent()
              , d = "input,textarea,select,datalist,keygen,[contenteditable='true']"
              , h = c.find("iframe")
              , p = ["blur." + s + " keydown." + s + " keyup." + s];
            h.length && h.each(function() {
                t(this).load(function() {
                    R(this) && t(this.contentDocument || this.contentWindow.document).bind(p[0], function(t) {
                        e(t)
                    })
                })
            }),
            l.attr("tabindex", "0").bind(p[0], function(t) {
                e(t)
            })
        }, z = function(e, i, a, r, o) {
            function s(t) {
                var i = "stepped" !== h.type
                  , n = o ? o : t ? i ? m / 1.5 : g : 1e3 / 60
                  , a = t ? i ? 7.5 : 40 : 2.5
                  , l = [Math.abs(p[0].offsetTop), Math.abs(p[0].offsetLeft)]
                  , u = [c.scrollRatio.y > 10 ? 10 : c.scrollRatio.y, c.scrollRatio.x > 10 ? 10 : c.scrollRatio.x]
                  , d = "x" === h.dir[0] ? l[1] + h.dir[1] * u[1] * a : l[0] + h.dir[1] * u[0] * a
                  , f = "x" === h.dir[0] ? l[1] + h.dir[1] * parseInt(h.scrollAmount) : l[0] + h.dir[1] * parseInt(h.scrollAmount)
                  , v = "auto" !== h.scrollAmount ? f : d
                  , _ = r ? r : t ? i ? "mcsLinearOut" : "mcsEaseInOut" : "mcsLinear"
                  , y = t ? !0 : !1;
                return t && 17 > n && (v = "x" === h.dir[0] ? l[1] : l[0]),
                V(e, v.toString(), {
                    dir: h.dir[0],
                    scrollEasing: _,
                    dur: n,
                    onComplete: y
                }),
                t ? void (h.dir = !1) : (clearTimeout(h.step),
                void (h.step = setTimeout(function() {
                    s()
                }, n)))
            }
            function l() {
                clearTimeout(h.step),
                K(h, "step"),
                X(e)
            }
            var c = e.data(n)
              , d = c.opt
              , h = c.sequential
              , p = t("#mCSB_" + c.idx + "_container")
              , f = "stepped" === h.type ? !0 : !1
              , m = d.scrollInertia < 26 ? 26 : d.scrollInertia
              , g = d.scrollInertia < 1 ? 17 : d.scrollInertia;
            switch (i) {
            case "on":
                if (h.dir = [a === u[16] || a === u[15] || 39 === a || 37 === a ? "x" : "y", a === u[13] || a === u[15] || 38 === a || 37 === a ? -1 : 1],
                X(e),
                ee(a) && "stepped" === h.type)
                    return;
                s(f);
                break;
            case "off":
                l(),
                (f || c.tweenRunning && h.dir) && s(!0)
            }
        }, H = function(e) {
            var i = t(this).data(n).opt
              , a = [];
            return "function" == typeof e && (e = e()),
            e instanceof Array ? a = e.length > 1 ? [e[0], e[1]] : "x" === i.axis ? [null, e[0]] : [e[0], null] : (a[0] = e.y ? e.y : e.x || "x" === i.axis ? null : e,
            a[1] = e.x ? e.x : e.y || "y" === i.axis ? null : e),
            "function" == typeof a[0] && (a[0] = a[0]()),
            "function" == typeof a[1] && (a[1] = a[1]()),
            a
        }, W = function(e, i) {
            if (null != e && "undefined" != typeof e) {
                var a = t(this)
                  , r = a.data(n)
                  , o = r.opt
                  , s = t("#mCSB_" + r.idx + "_container")
                  , l = s.parent()
                  , c = typeof e;
                i || (i = "x" === o.axis ? "x" : "y");
                var u = "x" === i ? s.outerWidth(!1) : s.outerHeight(!1)
                  , h = "x" === i ? s[0].offsetLeft : s[0].offsetTop
                  , p = "x" === i ? "left" : "top";
                switch (c) {
                case "function":
                    return e();
                case "object":
                    var f = e.jquery ? e : t(e);
                    if (!f.length)
                        return;
                    return "x" === i ? ie(f)[1] : ie(f)[0];
                case "string":
                case "number":
                    if (ee(e))
                        return Math.abs(e);
                    if (-1 !== e.indexOf("%"))
                        return Math.abs(u * parseInt(e) / 100);
                    if (-1 !== e.indexOf("-="))
                        return Math.abs(h - parseInt(e.split("-=")[1]));
                    if (-1 !== e.indexOf("+=")) {
                        var m = h + parseInt(e.split("+=")[1]);
                        return m >= 0 ? 0 : Math.abs(m)
                    }
                    if (-1 !== e.indexOf("px") && ee(e.split("px")[0]))
                        return Math.abs(e.split("px")[0]);
                    if ("top" === e || "left" === e)
                        return 0;
                    if ("bottom" === e)
                        return Math.abs(l.height() - s.outerHeight(!1));
                    if ("right" === e)
                        return Math.abs(l.width() - s.outerWidth(!1));
                    if ("first" === e || "last" === e) {
                        var f = s.find(":" + e);
                        return "x" === i ? ie(f)[1] : ie(f)[0]
                    }
                    return t(e).length ? "x" === i ? ie(t(e))[1] : ie(t(e))[0] : (s.css(p, e),
                    void d.update.call(null, a[0]))
                }
            }
        }, q = function(e) {
            function i() {
                return clearTimeout(p[0].autoUpdate),
                0 === l.parents("html").length ? void (l = null) : void (p[0].autoUpdate = setTimeout(function() {
                    return h.advanced.updateOnSelectorChange && (f = o(),
                    f !== b) ? (s(3),
                    void (b = f)) : (h.advanced.updateOnContentResize && (m = [p.outerHeight(!1), p.outerWidth(!1), v.height(), v.width(), y()[0], y()[1]],
                    (m[0] !== x[0] || m[1] !== x[1] || m[2] !== x[2] || m[3] !== x[3] || m[4] !== x[4] || m[5] !== x[5]) && (s(m[0] !== x[0] || m[1] !== x[1]),
                    x = m)),
                    h.advanced.updateOnImageLoad && (g = a(),
                    g !== w && (p.find("img").each(function() {
                        r(this)
                    }),
                    w = g)),
                    void ((h.advanced.updateOnSelectorChange || h.advanced.updateOnContentResize || h.advanced.updateOnImageLoad) && i()))
                }, h.advanced.autoUpdateTimeout))
            }
            function a() {
                var t = 0;
                return h.advanced.updateOnImageLoad && (t = p.find("img").length),
                t
            }
            function r(e) {
                function i(t, e) {
                    return function() {
                        return e.apply(t, arguments)
                    }
                }
                function n() {
                    this.onload = null,
                    t(e).addClass(u[2]),
                    s(2)
                }
                if (t(e).hasClass(u[2]))
                    return void s();
                var a = new Image;
                a.onload = i(a, n),
                a.src = e.src
            }
            function o() {
                h.advanced.updateOnSelectorChange === !0 && (h.advanced.updateOnSelectorChange = "*");
                var e = 0
                  , i = p.find(h.advanced.updateOnSelectorChange);
                return h.advanced.updateOnSelectorChange && i.length > 0 && i.each(function() {
                    e += t(this).height() + t(this).width()
                }),
                e
            }
            function s(t) {
                clearTimeout(p[0].autoUpdate),
                d.update.call(null, l[0], t)
            }
            var l = t(this)
              , c = l.data(n)
              , h = c.opt
              , p = t("#mCSB_" + c.idx + "_container");
            if (e)
                return clearTimeout(p[0].autoUpdate),
                void K(p[0], "autoUpdate");
            var f, m, g, v = p.parent(), _ = [t("#mCSB_" + c.idx + "_scrollbar_vertical"), t("#mCSB_" + c.idx + "_scrollbar_horizontal")], y = function() {
                return [_[0].is(":visible") ? _[0].outerHeight(!0) : 0, _[1].is(":visible") ? _[1].outerWidth(!0) : 0]
            }, b = o(), x = [p.outerHeight(!1), p.outerWidth(!1), v.height(), v.width(), y()[0], y()[1]], w = a();
            i()
        }, Y = function(t, e, i) {
            return Math.round(t / e) * e - i
        }, X = function(e) {
            var i = e.data(n)
              , a = t("#mCSB_" + i.idx + "_container,#mCSB_" + i.idx + "_container_wrapper,#mCSB_" + i.idx + "_dragger_vertical,#mCSB_" + i.idx + "_dragger_horizontal");
            a.each(function() {
                J.call(this)
            })
        }, V = function(e, i, a) {
            function r(t) {
                return l && c.callbacks[t] && "function" == typeof c.callbacks[t]
            }
            function o() {
                return [c.callbacks.alwaysTriggerOffsets || y >= b[0] + w, c.callbacks.alwaysTriggerOffsets || -T >= y]
            }
            function s() {
                var t = [p[0].offsetTop, p[0].offsetLeft]
                  , i = [v[0].offsetTop, v[0].offsetLeft]
                  , n = [p.outerHeight(!1), p.outerWidth(!1)]
                  , r = [h.height(), h.width()];
                e[0].mcs = {
                    content: p,
                    top: t[0],
                    left: t[1],
                    draggerTop: i[0],
                    draggerLeft: i[1],
                    topPct: Math.round(100 * Math.abs(t[0]) / (Math.abs(n[0]) - r[0])),
                    leftPct: Math.round(100 * Math.abs(t[1]) / (Math.abs(n[1]) - r[1])),
                    direction: a.dir
                }
            }
            var l = e.data(n)
              , c = l.opt
              , u = {
                trigger: "internal",
                dir: "y",
                scrollEasing: "mcsEaseOut",
                drag: !1,
                dur: c.scrollInertia,
                overwrite: "all",
                callbacks: !0,
                onStart: !0,
                onUpdate: !0,
                onComplete: !0
            }
              , a = t.extend(u, a)
              , d = [a.dur, a.drag ? 0 : a.dur]
              , h = t("#mCSB_" + l.idx)
              , p = t("#mCSB_" + l.idx + "_container")
              , f = p.parent()
              , m = c.callbacks.onTotalScrollOffset ? H.call(e, c.callbacks.onTotalScrollOffset) : [0, 0]
              , g = c.callbacks.onTotalScrollBackOffset ? H.call(e, c.callbacks.onTotalScrollBackOffset) : [0, 0];
            if (l.trigger = a.trigger,
            (0 !== f.scrollTop() || 0 !== f.scrollLeft()) && (t(".mCSB_" + l.idx + "_scrollbar").css("visibility", "visible"),
            f.scrollTop(0).scrollLeft(0)),
            "_resetY" !== i || l.contentReset.y || (r("onOverflowYNone") && c.callbacks.onOverflowYNone.call(e[0]),
            l.contentReset.y = 1),
            "_resetX" !== i || l.contentReset.x || (r("onOverflowXNone") && c.callbacks.onOverflowXNone.call(e[0]),
            l.contentReset.x = 1),
            "_resetY" !== i && "_resetX" !== i) {
                switch (!l.contentReset.y && e[0].mcs || !l.overflowed[0] || (r("onOverflowY") && c.callbacks.onOverflowY.call(e[0]),
                l.contentReset.x = null),
                !l.contentReset.x && e[0].mcs || !l.overflowed[1] || (r("onOverflowX") && c.callbacks.onOverflowX.call(e[0]),
                l.contentReset.x = null),
                c.snapAmount && (i = Y(i, c.snapAmount, c.snapOffset)),
                a.dir) {
                case "x":
                    var v = t("#mCSB_" + l.idx + "_dragger_horizontal")
                      , _ = "left"
                      , y = p[0].offsetLeft
                      , b = [h.width() - p.outerWidth(!1), v.parent().width() - v.width()]
                      , x = [i, 0 === i ? 0 : i / l.scrollRatio.x]
                      , w = m[1]
                      , T = g[1]
                      , S = w > 0 ? w / l.scrollRatio.x : 0
                      , k = T > 0 ? T / l.scrollRatio.x : 0;
                    break;
                case "y":
                    var v = t("#mCSB_" + l.idx + "_dragger_vertical")
                      , _ = "top"
                      , y = p[0].offsetTop
                      , b = [h.height() - p.outerHeight(!1), v.parent().height() - v.height()]
                      , x = [i, 0 === i ? 0 : i / l.scrollRatio.y]
                      , w = m[0]
                      , T = g[0]
                      , S = w > 0 ? w / l.scrollRatio.y : 0
                      , k = T > 0 ? T / l.scrollRatio.y : 0
                }
                x[1] < 0 || 0 === x[0] && 0 === x[1] ? x = [0, 0] : x[1] >= b[1] ? x = [b[0], b[1]] : x[0] = -x[0],
                e[0].mcs || (s(),
                r("onInit") && c.callbacks.onInit.call(e[0])),
                clearTimeout(p[0].onCompleteTimeout),
                (l.tweenRunning || !(0 === y && x[0] >= 0 || y === b[0] && x[0] <= b[0])) && (G(v[0], _, Math.round(x[1]), d[1], a.scrollEasing),
                G(p[0], _, Math.round(x[0]), d[0], a.scrollEasing, a.overwrite, {
                    onStart: function() {
                        a.callbacks && a.onStart && !l.tweenRunning && (r("onScrollStart") && (s(),
                        c.callbacks.onScrollStart.call(e[0])),
                        l.tweenRunning = !0,
                        C(v),
                        l.cbOffsets = o())
                    },
                    onUpdate: function() {
                        a.callbacks && a.onUpdate && r("whileScrolling") && (s(),
                        c.callbacks.whileScrolling.call(e[0]))
                    },
                    onComplete: function() {
                        if (a.callbacks && a.onComplete) {
                            "yx" === c.axis && clearTimeout(p[0].onCompleteTimeout);
                            var t = p[0].idleTimer || 0;
                            p[0].onCompleteTimeout = setTimeout(function() {
                                r("onScroll") && (s(),
                                c.callbacks.onScroll.call(e[0])),
                                r("onTotalScroll") && x[1] >= b[1] - S && l.cbOffsets[0] && (s(),
                                c.callbacks.onTotalScroll.call(e[0])),
                                r("onTotalScrollBack") && x[1] <= k && l.cbOffsets[1] && (s(),
                                c.callbacks.onTotalScrollBack.call(e[0])),
                                l.tweenRunning = !1,
                                p[0].idleTimer = 0,
                                C(v, "hide")
                            }, t)
                        }
                    }
                }))
            }
        }, G = function(t, e, i, n, a, r, o) {
            function s() {
                x.stop || (_ || f.call(),
                _ = Q() - v,
                l(),
                _ >= x.time && (x.time = _ > x.time ? _ + h - (_ - x.time) : _ + h - 1,
                x.time < _ + 1 && (x.time = _ + 1)),
                x.time < n ? x.id = p(s) : g.call())
            }
            function l() {
                n > 0 ? (x.currVal = d(x.time, y, w, n, a),
                b[e] = Math.round(x.currVal) + "px") : b[e] = i + "px",
                m.call()
            }
            function c() {
                h = 1e3 / 60,
                x.time = _ + h,
                p = window.requestAnimationFrame ? window.requestAnimationFrame : function(t) {
                    return l(),
                    setTimeout(t, .01)
                }
                ,
                x.id = p(s)
            }
            function u() {
                null != x.id && (window.requestAnimationFrame ? window.cancelAnimationFrame(x.id) : clearTimeout(x.id),
                x.id = null)
            }
            function d(t, e, i, n, a) {
                switch (a) {
                case "linear":
                case "mcsLinear":
                    return i * t / n + e;
                case "mcsLinearOut":
                    return t /= n,
                    t--,
                    i * Math.sqrt(1 - t * t) + e;
                case "easeInOutSmooth":
                    return t /= n / 2,
                    1 > t ? i / 2 * t * t + e : (t--,
                    -i / 2 * (t * (t - 2) - 1) + e);
                case "easeInOutStrong":
                    return t /= n / 2,
                    1 > t ? i / 2 * Math.pow(2, 10 * (t - 1)) + e : (t--,
                    i / 2 * (-Math.pow(2, -10 * t) + 2) + e);
                case "easeInOut":
                case "mcsEaseInOut":
                    return t /= n / 2,
                    1 > t ? i / 2 * t * t * t + e : (t -= 2,
                    i / 2 * (t * t * t + 2) + e);
                case "easeOutSmooth":
                    return t /= n,
                    t--,
                    -i * (t * t * t * t - 1) + e;
                case "easeOutStrong":
                    return i * (-Math.pow(2, -10 * t / n) + 1) + e;
                case "easeOut":
                case "mcsEaseOut":
                default:
                    var r = (t /= n) * t
                      , o = r * t;
                    return e + i * (.499999999999997 * o * r + -2.5 * r * r + 5.5 * o + -6.5 * r + 4 * t)
                }
            }
            t._mTween || (t._mTween = {
                top: {},
                left: {}
            });
            var h, p, o = o || {}, f = o.onStart || function() {}
            , m = o.onUpdate || function() {}
            , g = o.onComplete || function() {}
            , v = Q(), _ = 0, y = t.offsetTop, b = t.style, x = t._mTween[e];
            "left" === e && (y = t.offsetLeft);
            var w = i - y;
            x.stop = 0,
            "none" !== r && u(),
            c()
        }, Q = function() {
            return window.performance && window.performance.now ? window.performance.now() : window.performance && window.performance.webkitNow ? window.performance.webkitNow() : Date.now ? Date.now() : (new Date).getTime()
        }, J = function() {
            var t = this;
            t._mTween || (t._mTween = {
                top: {},
                left: {}
            });
            for (var e = ["top", "left"], i = 0; i < e.length; i++) {
                var n = e[i];
                t._mTween[n].id && (window.requestAnimationFrame ? window.cancelAnimationFrame(t._mTween[n].id) : clearTimeout(t._mTween[n].id),
                t._mTween[n].id = null,
                t._mTween[n].stop = 1)
            }
        }, K = function(t, e) {
            try {
                delete t[e]
            } catch (i) {
                t[e] = null
            }
        }, Z = function(t) {
            return !(t.which && 1 !== t.which)
        }, te = function(t) {
            var e = t.originalEvent.pointerType;
            return !(e && "touch" !== e && 2 !== e)
        }, ee = function(t) {
            return !isNaN(parseFloat(t)) && isFinite(t)
        }, ie = function(t) {
            var e = t.parents(".mCSB_container");
            return [t.offset().top - e.offset().top, t.offset().left - e.offset().left]
        };
        t.fn[i] = function(e) {
            return d[e] ? d[e].apply(this, Array.prototype.slice.call(arguments, 1)) : "object" != typeof e && e ? void t.error("Method " + e + " does not exist") : d.init.apply(this, arguments)
        }
        ,
        t[i] = function(e) {
            return d[e] ? d[e].apply(this, Array.prototype.slice.call(arguments, 1)) : "object" != typeof e && e ? void t.error("Method " + e + " does not exist") : d.init.apply(this, arguments)
        }
        ,
        t[i].defaults = r,
        window[i] = !0,
        t(window).load(function() {
            t(a)[i](),
            t.extend(t.expr[":"], {
                mcsInView: t.expr[":"].mcsInView || function(e) {
                    var i, n, a = t(e), r = a.parents(".mCSB_container");
                    if (r.length)
                        return i = r.parent(),
                        n = [r[0].offsetTop, r[0].offsetLeft],
                        n[0] + ie(a)[0] >= 0 && n[0] + ie(a)[0] < i.height() - a.outerHeight(!1) && n[1] + ie(a)[1] >= 0 && n[1] + ie(a)[1] < i.width() - a.outerWidth(!1)
                }
                ,
                mcsOverflow: t.expr[":"].mcsOverflow || function(e) {
                    var i = t(e).data(n);
                    if (i)
                        return i.overflowed[0] || i.overflowed[1]
                }
            })
        })
    })
}),
$(document).ready(function() {
    resizeActions(),
    initFunctions(),
    activintyToggleInit()
}),
$(window).load(function() {
    resizeActions(),
    initCustomScrollers(),
    $("#verification_form.autologin").length && $("#verification_form.autologin").submit(),
    insertActivityBarData()
}),
$(window).resize(function() {
    resizeActions(),
    resizeSearchMaxHeight($("#search_overlay")),
    resizeSearchMaxHeight($(".autoSearch"))
});
var changeItemSearchTimer, changeAccountTimer;
