import {FieldOption} from './FieldOption';
import {FieldValidation} from './FieldValidation';

export interface FieldDefinition {
    key: string;
    label: string;
    type: 'text' | 'number' | 'boolean' | 'select' | 'time';
    options?: FieldOption[];
    default?: unknown;
    validation?: FieldValidation;
}