<?php
add_action('wp_footer', 'custom_forminator_bank_account_script', 100);
function custom_forminator_bank_account_script() {
    if (!is_admin()) {
        ?>
        <script type="text/javascript">
        console.log('Script injected in footer at:', new Date().toISOString());

        if (!document.querySelector('#bank-account-styles')) {
            console.log('Injecting CSS once');
            var style = document.createElement('style');
            style.id = 'bank-account-styles';
            style.textContent = '.forminator-custom-form-ABCD .forminator-input{width:100%;max-width:300px;padding:10px;border:1px solid #ccc;border-radius:4px;}' +
                                '.forminator-custom-form-ABCD .forminator-input:disabled{color:#888;background-color:#f5f5f5;}' +
                                '.forminator-custom-form-ABCD .bank-error-message{color:red;font-size:14px;margin-top:5px;display:none;}' +
                                '.forminator-custom-form-ABCD .editable-wrapper{position:relative;}' +
                                '.forminator-custom-form-ABCD .editable-wrapper.disabled{cursor:pointer;}';
            document.head.appendChild(style);
        }

        function initializeFieldValidation(form, fieldPairs) {
            console.log('Initializing field validation at:', new Date().toISOString());

            if (!form) {
                console.log('Form "forminator-custom-form-ABCD" not found');
                return false;
            }
            console.log('Form ABCD found:', form);

            let allFieldsFound = true;

            fieldPairs.forEach(pair => {
                const { firstField, secondField, hiddenField, errorMessage } = pair;

                const firstEntry = form.querySelector(`input[name="${firstField}"]`);
                if (!firstEntry) {
                    console.log(`First entry "${firstField}" not found`);
                    allFieldsFound = false;
                    return;
                }
                console.log(`First entry found: Name = ${firstField}, ID =`, firstEntry.id);

                const secondEntry = form.querySelector(`input[name="${secondField}"]`);
                if (!secondEntry) {
                    console.log(`Second entry "${secondField}" not found`);
                    allFieldsFound = false;
                    return;
                }
                console.log(`Second entry found: Name = ${secondField}, ID =`, secondEntry.id);

                let hiddenEntry = form.querySelector(`input[name="${hiddenField}"]`);
                if (!hiddenEntry) {
                    console.log(`Hidden field "${hiddenField}" not found, creating one`);
                    hiddenEntry = document.createElement('input');
                    hiddenEntry.type = 'hidden';
                    hiddenEntry.name = hiddenField;
                    hiddenEntry.id = `${hiddenField}-custom`;
                    form.appendChild(hiddenEntry);
                }
                console.log(`Hidden entry ready: Name = ${hiddenField}, ID =`, hiddenEntry.id || 'none');

                let errorMsg = secondEntry.closest('.forminator-field').querySelector('.bank-error-message');
                if (!errorMsg) {
                    console.log(`Adding error message element for ${secondField}`);
                    errorMsg = document.createElement('div');
                    errorMsg.className = 'bank-error-message';
                    errorMsg.textContent = errorMessage;
                    secondEntry.closest('.forminator-field').appendChild(errorMsg);
                }

                let wrapper = firstEntry.closest('.editable-wrapper');
                if (!wrapper) {
                    console.log(`Wrapping ${firstField} in editable container`);
                    wrapper = document.createElement('div');
                    wrapper.className = 'editable-wrapper';
                    firstEntry.parentNode.insertBefore(wrapper, firstEntry);
                    wrapper.appendChild(firstEntry);
                }

                if (!firstEntry.dataset.listenerAdded) {
                    firstEntry.addEventListener('blur', function() {
                        console.log(`Blur event on ${firstField} triggered. Value:`, firstEntry.value);
                        if (firstEntry.value && firstEntry.value !== '•'.repeat(firstEntry.value.length)) {
                            hiddenEntry.value = firstEntry.value;
                            firstEntry.dataset.originalValue = firstEntry.value;
                            firstEntry.value = '•'.repeat(firstEntry.value.length);
                            firstEntry.disabled = true;
                            wrapper.classList.add('disabled');
                            console.log(`Masked ${firstField}. Stored in hidden:`, hiddenEntry.value);
                        }
                    });

                    wrapper.addEventListener('click', function() {
                        if (firstEntry.disabled) {
                            console.log(`Click event on wrapper for ${firstField}. Restoring value:`, firstEntry.dataset.originalValue);
                            firstEntry.disabled = false;
                            firstEntry.value = firstEntry.dataset.originalValue || '';
                            wrapper.classList.remove('disabled');
                            firstEntry.focus();
                        }
                    });

                    firstEntry.dataset.listenerAdded = 'true';
                }

                if (!secondEntry.dataset.listenerAdded) {
                    secondEntry.addEventListener('input', function() {
                        var firstValue = (hiddenEntry.value || firstEntry.dataset.originalValue || '').replace(/\s|-/g, '').toLowerCase();
                        var secondValue = secondEntry.value.replace(/\s|-/g, '').toLowerCase();
                        console.log(`Input event on ${secondField}. First value:`, firstValue, 'Second value:', secondValue);
                        if (firstValue && firstValue !== secondValue) {
                            errorMsg.style.display = 'block';
                            console.log('Mismatch detected - Error shown');
                        } else {
                            errorMsg.style.display = 'none';
                            console.log('Match detected or no first value - Error hidden');
                        }
                    });
                    secondEntry.dataset.listenerAdded = 'true';
                }
            });

            console.log('Field validation initialized successfully');
            return allFieldsFound;
        }

        function debounce(func, wait) {
            var timeout;
            return function() {
                clearTimeout(timeout);
                timeout = setTimeout(func, wait);
            };
        }

        const fieldPairs = [
            {
                firstField: 'text-3',
                secondField: 'text-8',
                hiddenField: 'hidden-1',
                errorMessage: 'Bank account numbers do not match.'
            },
            {
                firstField: 'text-4',
                secondField: 'text-9',
                hiddenField: 'hidden-2',
                errorMessage: 'IFSC codes do not match.'
            }
        ];

        document.addEventListener('DOMContentLoaded', function() {
            console.log('Initial DOM load, checking form');
            const form = document.querySelector('.forminator-custom-form-ABCD');
            initializeFieldValidation(form, fieldPairs);
        });

        var observer = new MutationObserver(debounce(function(mutations) {
            console.log('DOM mutation detected at:', new Date().toISOString());
            const form = document.querySelector('.forminator-custom-form-ABCD');
            if (initializeFieldValidation(form, fieldPairs)) {
                console.log('Validation setup complete, disconnecting observer');
                observer.disconnect();
            }
        }, 500));
        observer.observe(document.body, { childList: true, subtree: true });
        console.log('Mutation observer active with debounce');
        </script>
        <?php
    }
}
?>
