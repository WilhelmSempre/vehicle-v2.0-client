import Notification from './modules/notification.js';

$(document).ready(() => {
    const $notificationBlock = $('.notification');

    let notificationValue = $notificationBlock.html();

    const NotificationObject = new Notification();

    if ($notificationBlock.hasClass('error')) {
        NotificationObject.setErrorAlert(notificationValue);
    }

    if ($notificationBlock.hasClass('success')) {
        NotificationObject.setSuccessAlert(notificationValue);
    }

    if ($notificationBlock.hasClass('info')) {
        NotificationObject.setInfoAlert(notificationValue);
    }
});
