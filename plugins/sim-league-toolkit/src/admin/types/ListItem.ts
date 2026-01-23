import {TranslatableText} from "@wordpress/i18n";

export interface ListItem {
    value: number|string;
    label: string | TranslatableText<string>;
}