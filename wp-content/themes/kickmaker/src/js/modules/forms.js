/**
 * Form Input Helper Module
 *
 * Updates custom data attributes on file inputs when a file is selected.
 */

export function initFormFileInputs() {
    document.addEventListener("change", function (event) {
        if (event.target && event.target.matches && event.target.matches('input[type="file"]')) {
            const input = event.target;
            if (input.files && input.files.length > 0) {
                input.setAttribute("data-file-name", input.files[0].name);
            } else {
                input.removeAttribute("data-file-name");
            }
        }
    });
}
