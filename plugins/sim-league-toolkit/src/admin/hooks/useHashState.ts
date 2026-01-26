import { useState, useEffect, useCallback } from '@wordpress/element';

export function useHashState<T extends string>(
    key: string,
    defaultValue: T
): [T, (value: T) => void] {
    const getHashValue = useCallback((): T => {
        const hash = window.location.hash.slice(1);
        const params = new URLSearchParams(hash);
        const value = params.get(key);
        return (value as T) ?? defaultValue;
    }, [key, defaultValue]);

    const [state, setState] = useState<T>(getHashValue);

    useEffect(() => {
        const handleHashChange = (): void => {
            setState(getHashValue());
        };

        window.addEventListener('hashchange', handleHashChange);
        return () => window.removeEventListener('hashchange', handleHashChange);
    }, [getHashValue]);

    const setHashState = useCallback((newValue: T): void => {
        const hash = window.location.hash.slice(1);
        const params = new URLSearchParams(hash);
        params.set(key, newValue);
        window.location.hash = params.toString();
        setState(newValue);
    }, [key]);

    return [state, setHashState];
}