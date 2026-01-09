import {__} from '@wordpress/i18n';

import {Button} from 'primereact/button';
import { Card } from 'primereact/card';

export const RuleSetCard = ({ruleSet, onRequestEdit, onRequestDelete}) => {

    const footer = (
        <>
            <Button label={__('Edit', 'sim-league-toolkit')} icon="pi pi-pencil" severity='primary' onClick={() => onRequestEdit(ruleSet)} />
            <Button label={__('Delete', 'sim-league-toolkit')} icon="pi pi-times" severity='danger' onClick={() => onRequestDelete(ruleSet)} style={{marginLeft: '1rem'}} />
        </>
    );

    return (
        <Card title={ruleSet.name} subTitle={__('Type', 'sim-league-toolkit') + ': ' + ruleSet.type} footer={footer} style={{margin: '1rem', maxWidth: '300px'}}>
            <p>
                {ruleSet.description}
            </p>
        </Card>
    )
}