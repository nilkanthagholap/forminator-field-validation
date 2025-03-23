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
            style.textContent = '.forminator-custom-form-9281 .forminator-input{width:100%;max-width:300px;padding:10px;border:1px solid #ccc;border-radius:4px;}' +
                                '.forminator-custom-form-9281 .forminator-input:disabled{color:#888;background-color:#f5f5f5;}' +
                                '.forminator-custom-form-9281 .bank-error-message{color:red;font-size:14px;margin-top:5px;display:none;}' +
                                '.forminator-custom-form-9281 .editable-wrapper{position:relative;}' +
                                '.forminator-custom-form-9281 .editable-wrapper.disabled{cursor:pointer;}';
            document.head.appendChild(style);
        }

        function initializeBankAccountValidation() {
            console.log('Initializing bank account validation at:', new Date().toISOString());

            var form = document.querySelector('.forminator-custom-form-9281');
            if (!form) {
                console.log('Form "forminator-custom-form-9281" not found');
                return false;
            }
            console.log('Form 9281 found:', form);

            var firstEntry = form.querySelector('input[name="text-3"]');
            if (!firstEntry) {
                console.log('First entry "text-3" not found');
                return false;
            }
            console.log('First entry found: Name =', firstEntry.name, 'ID =', firstEntry.id);

            var secondEntry = form.querySelector('input[name="text-8"]');
            if (!secondEntry) {
                console.log('Second entry "text-8" not found');
                return false;
            }
            console.log('Second entry found: Name =', secondEntry.name, 'ID =', secondEntry.id);

            var hiddenEntry = form.querySelector('input[name="hidden-1"]');
            if (!hiddenEntry) {
                console.log('Hidden field "hidden-1" not found, creating one');
                hiddenEntry = document.createElement('input');
                hiddenEntry.type = 'hidden';
                hiddenEntry.name = 'hidden-1';
                hiddenEntry.id = 'hidden-1-custom';
                form.appendChild(hiddenEntry);
            }
            console.log('Hidden entry ready: Name =', hiddenEntry.name, 'ID =', hiddenEntry.id || 'none');

            var errorMessage = secondEntry.closest('.forminator-field').querySelector('.bank-error-message');
            if (!errorMessage) {
                console.log('Adding error message element');
                errorMessage = document.createElement('div');
                errorMessage.className = 'bank-error-message';
                errorMessage.textContent = 'Bank account numbers do not match.';
                secondEntry.closest('.forminator-field').appendChild(errorMessage);
            }

            var wrapper = firstEntry.closest('.editable-wrapper');
            if (!wrapper) {
                console.log('Wrapping text-3 in editable container');
                wrapper = document.createElement('div');
                wrapper.className = 'editable-wrapper';
                firstEntry.parentNode.insertBefore(wrapper, firstEntry);
                wrapper.appendChild(firstEntry);
            }

            if (!firstEntry.dataset.listenerAdded) {
                firstEntry.addEventListener('blur', function() {
                    console.log('Blur event on text-3 triggered. Value:', firstEntry.value);
                    if (firstEntry.value && firstEntry.value !== '•'.repeat(firstEntry.value.length)) {
                        hiddenEntry.value = firstEntry.value;
                        firstEntry.dataset.originalValue = firstEntry.value;
                        firstEntry.value = '•'.repeat(firstEntry.value.length);
                        firstEntry.disabled = true;
                        wrapper.classList.add('disabled');
                        console.log('Masked text-3. Stored in hidden:', hiddenEntry.value);
                    }
                });

                wrapper.addEventListener('click', function() {
                    if (firstEntry.disabled) {
                        console.log('Click event on wrapper. Restoring value:', firstEntry.dataset.originalValue);
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
                    var firstValue = (hiddenEntry.value || firstEntry Ruckdataset.originalValue || '').replace(/\s|-/g, '').toLowerCase();
                    var secondValue = secondEntry.value.replace(/\s|-/g, '').toLowerCase();
                    console.log('Input event on text-8. First value:', firstValue, 'Second value:', secondValue);
                    if (firstValue && firstValue !== secondValue) {
                        errorMessage.style.display = 'block';
                        console.log('Mismatch detected - Error shown');
                    } else {
                        errorMessage.style.display = 'none';
                        console.log('Match detected or no first value - Error hidden');
                    }
                });
                secondEntry.dataset.listenerAdded = 'true';
            }

            console.log('Bank account validation initialized successfully');
            return true;
        }

        function debounce(func, wait) {
            var timeout;
            return function() {
                clearTimeout(timeout);
                timeout = setTimeout(func, wait);
            };
        }

        document.addEventListener('DOMContentLoaded', function() {
            console.log('Initial DOM load, checking form');
            initializeBankAccountValidation();
        });

        var observer = new MutationObserver(debounce(function(mutations) {
            console.log('DOM mutation detected at:', new Date().toISOString());
            if (initializeBankAccountValidation()) {
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
