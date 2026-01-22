import apiFetch from '@wordpress/api-fetch';
import { dispatch } from '@wordpress/data';
import { store as noticesStore } from '@wordpress/notices';
import { __ } from '@wordpress/i18n';

export interface ApiError {
    code: string;
    message: string;
    errors?: Record<string, string[]>;
}

export interface ApiResponse<T> {
    success: boolean;
    data?: T;
    error?: ApiError;
}

const API_NAMESPACE = '/sltk/v1';

const showErrorNotice = (message: string): void => {
    dispatch(noticesStore).createErrorNotice(message, {
        isDismissible: true,
        type: 'snackbar',
    });
};

const showSuccessNotice = (message: string): void => {
    dispatch(noticesStore).createSuccessNotice(message, {
        isDismissible: true,
        type: 'snackbar',
    });
};

const handleApiError = (error: unknown): ApiError => {
    if (error && typeof error === 'object' && 'code' in error) {
        return error as ApiError;
    }

    return {
        code: 'unknown_error',
        message: __('An unexpected error occurred', 'sim-league-toolkit'),
    };
};

export const apiClient = {
    async get<T>(endpoint: string): Promise<ApiResponse<T>> {
        try {
            const data = await apiFetch<T>({
                                               path: `${API_NAMESPACE}${endpoint}`,
                                               method: 'GET',
                                           });

            return { success: true, data };
        } catch (error) {
            const apiError = handleApiError(error);
            showErrorNotice(apiError.message);
            return { success: false, error: apiError };
        }
    },

    async post<T>(endpoint: string, body: unknown): Promise<ApiResponse<T>> {
        try {
            const data = await apiFetch<T>({
                                               path: `${API_NAMESPACE}${endpoint}`,
                                               method: 'POST',
                                               data: body,
                                           });

            return { success: true, data };
        } catch (error) {
            const apiError = handleApiError(error);
            showErrorNotice(apiError.message);
            return { success: false, error: apiError };
        }
    },

    async put<T>(endpoint: string, body: unknown): Promise<ApiResponse<T>> {
        try {
            const data = await apiFetch<T>({
                                               path: `${API_NAMESPACE}${endpoint}`,
                                               method: 'PUT',
                                               data: body,
                                           });

            return { success: true, data };
        } catch (error) {
            const apiError = handleApiError(error);
            showErrorNotice(apiError.message);
            return { success: false, error: apiError };
        }
    },

    async delete(endpoint: string): Promise<ApiResponse<void>> {
        try {
            await apiFetch({
                               path: `${API_NAMESPACE}${endpoint}`,
                               method: 'DELETE',
                           });

            return { success: true };
        } catch (error) {
            const apiError = handleApiError(error);
            showErrorNotice(apiError.message);
            return { success: false, error: apiError };
        }
    },

    showSuccess: showSuccessNotice,
    showError: showErrorNotice,
};