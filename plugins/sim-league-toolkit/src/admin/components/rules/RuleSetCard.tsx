import {__} from '@wordpress/i18n';

import {Button} from 'primereact/button';
import {Card} from 'primereact/card';

import {RuleSet} from "../../types/RuleSet";

interface RuleSetCardProps {
    ruleSet: RuleSet;
    onRequestEdit: (item: RuleSet) => void;
    onRequestDelete: (item: RuleSet) => void;
}

export const RuleSetCard = ({ruleSet, onRequestEdit, onRequestDelete}: RuleSetCardProps) => {

    const footer = (
        <>
            <Button label={__('Edit', 'sim-league-toolkit')} icon='pi pi-pencil'
                    onClick={() => onRequestEdit(ruleSet)}/>
            <Button label={__('Delete', 'sim-league-toolkit')} icon='pi pi-times' severity='danger'
                    onClick={() => onRequestDelete(ruleSet)} style={{marginLeft: '1rem'}}/>
        </>
    );

    return (
        <Card title={ruleSet.name} footer={footer} style={{margin: '1rem', maxWidth: '300px'}}>
            <p>
                {ruleSet.description}
            </p>
        </Card>
    )
}