import {useEffect, useRef, ReactNode} from 'react';
import { useSelect, useDispatch } from '@wordpress/data';
import { store as noticesStore } from '@wordpress/notices';
import { Toast } from 'primereact/toast';
import type { ToastMessage } from 'primereact/toast';

interface WPNotice {
    id: string;
    status: 'success' | 'error' | 'warning' | 'info';
    content: string | ReactNode;
    isDismissible: boolean;
    type: string;
    speak: boolean;
    actions: Array<{
        label: string;
        url?: string;
        onClick?: () => void;
    }>;
}

type ToastPosition =
    | 'center'
    | 'top-center'
    | 'top-left'
    | 'top-right'
    | 'bottom-center'
    | 'bottom-left'
    | 'bottom-right';

type PrimeReactSeverity = 'success' | 'info' | 'warn' | 'error';

interface NotificationsProps {
    position?: ToastPosition;
    life?: number;
}

interface NoticesStoreSelectors {
    getNotices: () => WPNotice[];
}

const mapStatusToSeverity = (status: WPNotice['status']): PrimeReactSeverity => {
    const mapping: Record<WPNotice['status'], PrimeReactSeverity> = {
        success: 'success',
        error: 'error',
        warning: 'warn',
        info: 'info',
    };
    return mapping[status] || 'info';
};

const getContentAsString = (content: WPNotice['content']): string => {
    if (typeof content === 'string') {
        return content;
    }
    return String(content) || '';
};

export const Notifications = ({
                                                                 position = 'top-right',
                                                                 life = 5000,
                                                             }: NotificationsProps) => {
    const toastRef = useRef<Toast>(null);
    const shownNoticesRef = useRef<Set<string>>(new Set());

    const notices = useSelect(
        (select) => {
            const store = select(noticesStore) as unknown as NoticesStoreSelectors;
            return store.getNotices();
        },
        []
    );

    const { removeNotice } = useDispatch(noticesStore);

    useEffect(() => {
        notices.forEach((notice: WPNotice) => {
            if (shownNoticesRef.current.has(notice.id)) {
                return;
            }

            shownNoticesRef.current.add(notice.id);

            const toastMessage: ToastMessage = {
                severity: mapStatusToSeverity(notice.status),
                summary: notice.status.charAt(0).toUpperCase() + notice.status.slice(1),
                detail: getContentAsString(notice.content),
                life,
                closable: notice.isDismissible,
            };

            toastRef.current?.show(toastMessage);

            setTimeout(() => {
                removeNotice(notice.id).then(_ => {});
            }, 100);
        });
    }, [notices, removeNotice, life]);

    useEffect(() => {
        return () => {
            shownNoticesRef.current.clear();
        };
    }, []);

    return <Toast ref={toastRef} position={position} />;
};
