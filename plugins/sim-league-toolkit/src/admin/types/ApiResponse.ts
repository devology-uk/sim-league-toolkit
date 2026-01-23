import {ApiError} from './ApiError';

export interface ApiResponse<T> {
    success: boolean;
    data?: T;
    error?: ApiError;
}