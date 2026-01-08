import {__} from '@wordpress/i18n';

import {Button} from 'primereact/button';
import {Card} from 'primereact/card';

export const ChampionshipCard = ({championship, onRequestEdit, onRequestDelete}) => {
    const footer = (
        <>
            <Button label={__('Edit', 'sim-league-toolkit')} icon='pi pi-pencil' severity='primary'
                    onClick={() => onRequestEdit(championship)}/>
            <Button label={__('Delete', 'sim-league-toolkit')} icon='pi pi-times' severity='danger'
                    onClick={() => onRequestDelete(championship)} style={{marginLeft: '1rem'}}/>
        </>
    );

    return (
        <Card title={championship.name} subTitle={championship.game}
              footer={footer}
              style={{margin: '1rem', maxWidth: '400px'}}>
            <table className='table-no-border'>
                <tbody>
                <tr>
                    <th scope='row'>{__('Description', 'sim-league-toolkit')}</th>
                    <td>{championship.description}</td>
                </tr>
                </tbody>
            </table>
        </Card>
    )
}