export default class Notification {

    /**
     * @param message
     */
    setSuccessAlert(message) {
        $(document).Toasts('create', {
            title: message,
            class: 'toast-success',
            close: false,
            autohide: true,
            delay: 5000,
            icon: 'fas fa-check'
        });
    }

    /**
     * @param message
     */
    setErrorAlert(message) {
        $(document).Toasts('create', {
            title: message,
            class: 'toast-error',
            close: false,
            autohide: true,
            delay: 5000,
            icon: 'fas fa-exclamation-triangle'
        });
    }

    /**
     * @param message
     */
    setInfoAlert(message) {
        $(document).Toasts('create', {
            title: message,
            class: 'toast-info',
            close: false,
            autohide: true,
            delay: 5000,
            icon: 'fas fa-info'
        });
    }
}