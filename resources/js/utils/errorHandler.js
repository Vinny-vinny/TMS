export function handleErrorResponse(error, validationErrors, swal) {
    if (error.response) {
        const errorMessage = error.response.data.message || 'An unexpected error occurred. Please try again.';
        switch (error.response.status) {
            case 422:
                validationErrors.value = error.response.data.data;
                break;
            case 401:
                swal({
                    icon: 'error',
                    title: 'Unauthorized',
                    text: errorMessage
                });
                break;
            case 404:
                swal({
                    icon: 'error',
                    title: 'Not Found',
                    text: errorMessage
                });
                break;
            case 500:
                swal({
                    icon: 'error',
                    title: 'Server Error',
                    text: errorMessage
                });
                break;
            default:
                swal({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage
                });
        }
    }
}
