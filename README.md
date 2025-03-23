# Forminator Field Validation and Masking

This repository contains custom PHP/JavaScript code snippets to enhance the functionality of Forminator forms (specifically `[forminator_form id="ABCD"]`) by adding field validation, masking, and editable disabled fields. The code was developed to address specific requirements for a multi-page form, such as masking sensitive input (e.g., bank account or IFSC codes) and validating confirmation fields.

## Files
1. **`single-pair-bank-account-script.php`**  
   - Handles a single pair of fields (e.g., `{text-3}` and `{text-8}` for bank account numbers).
   - Features:
     - Masks the first field with bullets (`•••••`) on blur.
     - Validates the second field against the first.
     - Allows editing the masked field by clicking a wrapper.
   - Ideal for simpler use cases with one set of fields.

2. **`multi-pair-field-validation-script.php`**  
   - Extends the functionality to handle multiple field pairs (e.g., bank account `{text-3}/{text-8}` and IFSC `{text-4}/{text-9}`).
   - Features:
     - Configurable field pairs via a `fieldPairs` array.
     - Same masking, validation, and editing capabilities as the single-pair version.
   - Suitable for forms requiring multiple validated field sets.

## Usage
1. Install the [Forminator plugin](https://wordpress.org/plugins/forminator/) on your WordPress site.
2. Add either script to your WordPress site using a plugin like [Code Snippets](https://wordpress.org/plugins/code-snippets/) or your theme’s `functions.php`.
3. Adjust the form ID `[forminator_form id="ABCD"]` to your form ID. Just replace ABCD with the number from the shortcode
4. Adjust field names in the script (e.g., `text-3`, `text-8`, `text-4`, `text-9`) to match your Forminator form fields.
5. In my code, `text-3` and `text-8` are fields where the user enters `bank account number` and `confirm bank account number`, respectively. In your case, these field IDs may be different.
6. you need to add a hidden field between the 2 fields you want to verify. ie `bank account number` `hidden-1` `confirm bank account number` [`{text-3}` `{hidden-1}` `{text-8}`]
7. Test on the relevant form page the respective form `[forminator_form id="ABCD"]`).

## Credits
- **Developed with assistance from**: Grok 3, built by [xAI](https://xai.ai/). Grok provided invaluable debugging, optimization, and logic structuring throughout the development process.
- **Special thanks to**: The [Forminator team](https://github.com/wpmudev/forminator-ui) at WPMU DEV for creating a flexible form plugin that made this customization possible.

## Tags
- @wpmudev (Forminator team)
- @xAI (Grok’s creators)

## Contributing
Feel free to fork this repository, submit issues, or send pull requests with improvements!

## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details (optional).
