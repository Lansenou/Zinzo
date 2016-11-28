window.linkOpener = function ($) {
    return function (event, id, url, target) {
        event = event || window.event;
        if (url.search('preview') === -1 && $(event.target).closest('.image.component').hasClass('id' + id)) {
            event.stopPropagation();
            window.open(url, target);
        } else {
            $(event.target).attr('href', url);
        }
    };
}(oneJQuery);
window.OnewebContactForm = function ($) {
    var ContactFormValidation;
    var htmlEncode = function (str) {
        return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    };
    ContactFormValidation = function (cfg) {
        try {
            this.init(cfg);
        } catch (e) {
            throw new Error('ContactForm Validation initialization failed');
        }
    };
    ContactFormValidation.prototype = {
        constructor: window.OnewebContactForm,
        init: function (cfg) {
            this.formDOMId = cfg.formDOMId;
            this.postURL = cfg.postURL;
            this.recipientEmail = decodeURIComponent(cfg.recipientEmail);
            this.successMessage = decodeURIComponent(cfg.successMessage);
            this.errorMessage = decodeURIComponent(cfg.errorMessage);
            this.formElementsErrorMessages = JSON.parse(cfg.formElementsErrorMessages);
            this.allFieldErrorMessage = JSON.parse(cfg.allFieldErrorMessage);
            this.emailRegex = new RegExp(cfg.emailRegex, 'i');
            this.urlRegex = new RegExp(cfg.urlRegex);
            this.numberRegex = /^[0-9+\(\)#\.\s]+$/;
            this.numberQuery = 'input[ctype="number"]';
            this.previewMode = cfg.previewMode;
            this.attachSubmitEvent();
            this.attachNumberInputValidation();
            this.formFieldErrors = [];
            this.contactFormDOM = {};
            this.formData = {
                recipient: this.recipientEmail,
                email: this.recipientEmail,
                subject: decodeURIComponent(cfg.subject)
            };
            this.defaultFormData = $.extend({}, this.formData);
            this.originalCharset = this.getDocumentCharset();
            this.attachSubmitEvent();
        },
        attachSubmitEvent: function () {
            $('.oneWebCntForm input[type="submit"]').click($.proxy(this.validateForm, this));
        },
        attachNumberInputValidation: function () {
            var regex = this.numberRegex;
            $('.contact-form-field-container > ' + this.numberQuery).on('input', function () {
                var $this = $(this), value = $this.val();
                if (!regex.test(value)) {
                    var pattern = '[^' + regex.source.replace(/^\^\[(.*?)\]\+\$$/, '$1') + ']';
                    var newValue = value.replace(new RegExp(pattern, 'g'), '');
                    $this.val(newValue);
                }
            });
        },
        validateForm: function (event) {
            event.preventDefault();
            event.stopImmediatePropagation();
            this.removeSuccessMessageOnFormFields($('#contactFormResponseContainer'));
            this.contactFormDOM = $('.oneWebCntForm');
            this.removeErrorMessageWarningOnFormFields(this.contactFormDOM);
            this.setDocumentCharset('ISO-8859-1');
            if (this.getFormValidationErrors(this.contactFormDOM) === 0) {
                this.updateFormData(this.contactFormDOM);
                if (this.isHiddenFieldEmpty() && !this.previewMode) {
                    this.postContactForm();
                    this.formData = $.extend(true, {}, this.defaultFormData);
                }
            } else {
                var errEl = $('.contact-form-field-container .error-message')[0];
                if (errEl) {
                    errEl.scrollIntoView();
                }
                this.setDocumentCharset(this.originalCharset);
                return false;
            }
        },
        removeErrorMessageWarningOnFormFields: function (formDOM) {
            $(formDOM).find('.error-message').remove();
        },
        removeSuccessMessageOnFormFields: function (successMsgDOM) {
            $(successMsgDOM).html('').removeClass('formSuccess');
        },
        getFormValidationErrors: function (formDOM) {
            var formFields = formDOM.find('.contact-form-field-container'), emailRegex = this.emailRegex, urlRegex = this.urlRegex, messageRegex = /^\S*/g, numberRegex = this.numberRegex, errors = 0;
            $.each(formFields, $.proxy(function (index, element) {
                var errorMessage = this.formElementsErrorMessages[index], inputFieldVal, errorFound = false, text;
                var $numberField = $(element).find(this.numberQuery), isNumberField = $numberField.length === 1;
                errorMessage = errorMessage && decodeURIComponent(errorMessage);
                if ($(element).find('input[type="text"]')[0] && !isNumberField && errorMessage) {
                    text = $.trim($(element).find('input[type="text"]').val());
                    if (!text.length && text.match(messageRegex)) {
                        errorFound = true;
                    }
                } else if ($(element).find('input[type="email"]')[0]) {
                    inputFieldVal = $(element).find('input[type="email"]').val();
                    if (errorMessage || inputFieldVal) {
                        errorMessage = errorMessage || decodeURIComponent(this.allFieldErrorMessage[index]);
                        if (!emailRegex.test(inputFieldVal)) {
                            errorFound = true;
                        }
                    }
                } else if ($(element).find('input[type="url"]')[0]) {
                    inputFieldVal = $(element).find('input[type="url"]').val();
                    if (errorMessage || inputFieldVal) {
                        errorMessage = errorMessage || decodeURIComponent(this.allFieldErrorMessage[index]);
                        if (!urlRegex.test(inputFieldVal) && !urlRegex.test('http://' + inputFieldVal)) {
                            errorFound = true;
                        }
                    }
                } else if (isNumberField) {
                    inputFieldVal = $numberField.val();
                    if (errorMessage || inputFieldVal) {
                        errorMessage = errorMessage || decodeURIComponent(this.allFieldErrorMessage[index]);
                        if (!inputFieldVal.match(numberRegex)) {
                            errorFound = true;
                        }
                    }
                } else if ($(element).find('input[type="tel"]')[0]) {
                    inputFieldVal = $(element).find('input[type="tel"]').val();
                    if (errorMessage || inputFieldVal) {
                        errorMessage = errorMessage || decodeURIComponent(this.allFieldErrorMessage[index]);
                        if (!inputFieldVal.match(numberRegex)) {
                            errorFound = true;
                        }
                    }
                } else if ($(element).find('input[type="checkbox"]').length > 0 && errorMessage) {
                    if (!$(element).find('input[type="checkbox"]:checked')[0]) {
                        errorFound = true;
                    }
                } else if ($(element).find('input[type="radio"]').length > 0 && errorMessage) {
                    if (!$(element).find('input[type="radio"]:checked')[0]) {
                        errorFound = true;
                    }
                } else if ($(element).find('textarea')[0] && errorMessage) {
                    text = $.trim($(element).find('textarea').val());
                    if (!text.length && text.match(messageRegex)) {
                        errorFound = true;
                    }
                } else if ($(element).find('select')[0] && errorMessage) {
                    var selectedValue = $(element).find('select').val();
                    if (!selectedValue && selectedValue !== '--') {
                        errorFound = true;
                    }
                }
                var errContainer = $(element).next();
                if (errorFound) {
                    errContainer.html(htmlEncode(errorMessage));
                    errors = errors + 1;
                } else {
                    errContainer.html('&nbsp;');
                }
            }, this));
            return errors;
        },
        updateFormData: function (formDOM) {
            var formFields = $(formDOM).find('.contact-form-field-container');
            $.each(formFields, $.proxy(function (index, element) {
                var labelName = $(element).find('label').text().replace(' *', '');
                if ($(element).find('input[type="text"]')[0] || $(element).find('input[type="url"]')[0] || $(element).find(this.numberQuery)[0] || $(element).find('input[type="tel"]')[0]) {
                    this.formData[labelName] = $(element).find('input').val();
                } else if ($(element).find('input[type="email"]')[0]) {
                    labelName = labelName === 'email' ? 'Email' : labelName;
                    if (!this.formData.replyto) {
                        this.formData.replyto = this.formData[labelName] = $(element).find('input').val();
                    } else {
                        this.formData[labelName] = $(element).find('input').val();
                    }
                } else if ($(element).find('input[type="checkbox"]:checked')[0]) {
                    this.formData[labelName] = $(element).find('input[type="checkbox"]:checked').map(function (index, ele) {
                        return $(ele).val();
                    }).get();
                } else if ($(element).find('input[type="radio"]:checked')[0]) {
                    this.formData[labelName] = $(element).find('input[type="radio"]:checked').val();
                } else if ($(element).find('select')[0]) {
                    this.formData[labelName] = $(element).find('select').val();
                } else if ($(element).find('textarea')[0]) {
                    this.formData[labelName] = $(element).find('textarea').val();
                }
            }, this));
        },
        getEncodedFormData: function () {
            var encodedFormData = '';
            for (var key in this.formData) {
                encodedFormData += unescape(encodeURIComponent(escape(key))).replace(/\+/g, '%2B') + '=' + unescape(encodeURIComponent(escape(this.formData[key]))).replace(/\+/g, '%2B') + '&';
            }
            encodedFormData = encodedFormData.substring(0, encodedFormData.length - 1);
            return encodedFormData;
        },
        isHiddenFieldEmpty: function () {
            return $(this.contactFormDOM).find('.oneweb-hidden-field>input').text() === '';
        },
        postContactForm: function () {
            $.ajax({
                type: 'POST',
                url: this.postURL,
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                },
                data: this.getEncodedFormData(),
                success: $.proxy(this.ajaxSuccess, this),
                error: $.proxy(this.ajaxError, this)
            });
        },
        ajaxSuccess: function () {
            var responseStatus = $('#contactFormResponseContainer');
            $(responseStatus).html(htmlEncode(this.successMessage)).addClass('formSuccess');
            this.resetDocument();
        },
        ajaxError: function () {
            var responseStatus = $('#contactFormResponseContainer');
            $(responseStatus).html(this.errorMessage).addClass('formError');
        },
        resetDocument: function () {
            $(this.contactFormDOM).trigger('reset');
            this.setDocumentCharset(this.originalCharset);
        },
        getDocumentCharset: function () {
            return document.characterSet || document.charset;
        },
        setDocumentCharset: function (charset) {
            if (document.charset) {
                document.charset = charset;
            } else {
                document.characterSet = charset;
            }
        }
    };
    return ContactFormValidation;
}(oneJQuery);