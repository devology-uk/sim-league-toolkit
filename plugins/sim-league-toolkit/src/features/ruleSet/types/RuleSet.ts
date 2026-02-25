import {RuleSetRule} from './RuleSetRule';

export interface RuleSet {
    id?: number;
    name: string;
    description: string;
    rules?: RuleSetRule[];
}

