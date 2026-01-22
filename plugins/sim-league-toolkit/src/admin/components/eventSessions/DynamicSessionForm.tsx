import { __ } from '@wordpress/i18n';
import { useState, useEffect } from '@wordpress/element';

import { Button } from 'primereact/button';
import { Calendar } from 'primereact/calendar';
import { Checkbox } from 'primereact/checkbox';
import { Dropdown } from 'primereact/dropdown';
import { InputNumber } from 'primereact/inputnumber';
import { InputText } from 'primereact/inputtext';

import {GameConfig} from '../../types/GameConfig';
import {EventSessionFormData} from '../../types/EventSessionFormData';
import {FieldDefinition} from '../../types/FieldDefinition';
import { SessionTypeOptions, SessionType } from '../../generated/enums';

interface DynamicSessionFormProps {
    eventRefId: number;
    gameId: string;
    gameConfig: GameConfig | null;
    initialData?: Partial<EventSessionFormData>;
    onSubmit: (data: EventSessionFormData) => Promise<void>;
    onCancel: () => void;
    loading?: boolean;
}

export const DynamicSessionForm = ({
                                       eventRefId,
                                       gameId,
                                       gameConfig,
                                       initialData,
                                       onSubmit,
                                       onCancel,
                                       loading = false,
                                   }: DynamicSessionFormProps) => {
    const [name, setName] = useState(initialData?.name ?? '');
    const [sessionType, setSessionType] = useState<SessionType>(
        initialData?.sessionType ?? SessionType.PRACTICE
    );
    const [startTime, setStartTime] = useState(initialData?.startTime ?? '08:00');
    const [duration, setDuration] = useState(initialData?.duration ?? 15);
    const [attributes, setAttributes] = useState<Record<string, unknown>>(
        initialData?.attributes ?? {}
    );

    const sessionTypeConfig = gameConfig?.sessionTypes?.[sessionType];
    const fields = sessionTypeConfig?.fields ?? [];

    useEffect(() => {
        const defaults: Record<string, unknown> = {};

        fields.forEach((field) => {
            defaults[field.key] = initialData?.attributes?.[field.key] ?? field.default ?? null;
        });

        setAttributes(defaults);
    }, [sessionType, gameConfig]);

    const handleAttributeChange = (key: string, value: unknown) => {
        setAttributes((prev) => ({
            ...prev,
            [key]: value,
        }));
    };

    const parseTimeString = (timeStr: string): Date | null => {
        if (!timeStr) return null;
        const [hours, minutes] = timeStr.split(':').map(Number);
        const date = new Date();
        date.setHours(hours, minutes, 0, 0);
        return date;
    };

    const formatTimeDate = (date: Date | null): string => {
        if (!date) return '08:00';
        return `${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}`;
    };

    const handleSubmit = async () => {
        const data: EventSessionFormData = {
            eventRefId,
            gameId,
            name,
            sessionType,
            startTime,
            duration,
            sortOrder: initialData?.sortOrder ?? 0,
            attributes,
        };

        await onSubmit(data);
    };

    const renderField = (field: FieldDefinition) => {
        const value = attributes[field.key];

        switch (field.type) {
            case 'select':
                return (
                    <div key={field.key} className="field">
                        <label htmlFor={field.key}>{field.label}</label>
                        <Dropdown
                            id={field.key}
                            value={value}
                            options={field.options ?? []}
                            optionLabel="label"
                            optionValue="value"
                            onChange={(e) => handleAttributeChange(field.key, e.value)}
                            className="w-full"
                        />
                    </div>
                );

            case 'boolean':
                return (
                    <div key={field.key} className="field-checkbox">
                        <Checkbox
                            id={field.key}
                            checked={value as boolean}
                            onChange={(e) => handleAttributeChange(field.key, e.checked)}
                        />
                        <label htmlFor={field.key} className="ml-2">
                            {field.label}
                        </label>
                    </div>
                );

            case 'number':
                return (
                    <div key={field.key} className="field">
                        <label htmlFor={field.key}>{field.label}</label>
                        <InputNumber
                            id={field.key}
                            value={value as number}
                            onValueChange={(e) => handleAttributeChange(field.key, e.value)}
                            min={field.validation?.min}
                            max={field.validation?.max}
                            className="w-full"
                        />
                    </div>
                );

            case 'time':
                return (
                    <div key={field.key} className="field">
                        <label htmlFor={field.key}>{field.label}</label>
                        <Calendar
                            id={field.key}
                            value={parseTimeString(value as string)}
                            onChange={(e) => handleAttributeChange(field.key, formatTimeDate(e.value as Date))}
                            timeOnly
                            hourFormat="24"
                            className="w-full"
                        />
                    </div>
                );

            default:
                return (
                    <div key={field.key} className="field">
                        <label htmlFor={field.key}>{field.label}</label>
                        <InputText
                            id={field.key}
                            value={(value as string) ?? ''}
                            onChange={(e) => handleAttributeChange(field.key, e.target.value)}
                            className="w-full"
                        />
                    </div>
                );
        }
    };

    return (
        <div className="dynamic-session-form">
            <div className="field">
                <label htmlFor="name">{__('Session Name', 'sim-league-toolkit')}</label>
                <InputText
                    id="name"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    className="w-full"
                />
            </div>

            <div className="field">
                <label htmlFor="sessionType">{__('Session Type', 'sim-league-toolkit')}</label>
                <Dropdown
                    id="sessionType"
                    value={sessionType}
                    options={[...SessionTypeOptions]}
                    optionLabel="label"
                    optionValue="value"
                    onChange={(e) => setSessionType(e.value)}
                    className="w-full"
                />
            </div>

            <div className="formgrid grid">
                <div className="field col-6">
                    <label htmlFor="startTime">{__('Start Time', 'sim-league-toolkit')}</label>
                    <Calendar
                        id="startTime"
                        value={parseTimeString(startTime)}
                        onChange={(e) => setStartTime(formatTimeDate(e.value as Date))}
                        timeOnly
                        hourFormat="24"
                        className="w-full"
                    />
                </div>

                <div className="field col-6">
                    <label htmlFor="duration">{__('Duration (minutes)', 'sim-league-toolkit')}</label>
                    <InputNumber
                        id="duration"
                        value={duration}
                        onValueChange={(e) => setDuration(e.value ?? 15)}
                        min={1}
                        className="w-full"
                    />
                </div>
            </div>

            {fields.length > 0 && (
                <div className="game-specific-fields mt-4">
                    <h4>{__('Game-Specific Settings', 'sim-league-toolkit')}</h4>
                    {fields.map(renderField)}
                </div>
            )}

            <div className="flex justify-content-end gap-2 mt-4">
                <Button
                    label={__('Cancel', 'sim-league-toolkit')}
                    severity="secondary"
                    onClick={onCancel}
                    disabled={loading}
                />
                <Button
                    label={__('Save', 'sim-league-toolkit')}
                    onClick={handleSubmit}
                    loading={loading}
                />
            </div>
        </div>
    );
};