import {Button} from 'primereact/button';
import {__} from '@wordpress/i18n';
import {Card} from 'primereact/card';

export const ServerCard = ({server, onRequestEdit, onRequestDelete}) => {
    const footer = (
        <>
            <Button label={__('Edit', 'sim-league-toolkit')} icon='pi pi-pencil' severity='primary'
                    onClick={() => onRequestEdit(server)}/>
            <Button label={__('Delete', 'sim-league-toolkit')} icon='pi pi-times' severity='danger'
                    onClick={() => onRequestDelete(server)} style={{marginLeft: '1rem'}}/>
        </>
    );

    return (
        <Card title={server.name} subTitle={server.game} footer={footer} style={{margin: '1rem', maxWidth: '300px'}}>
            <table className='table-no-border'>
                <tbody>
                <tr>
                    <th scope='row'>{__('Platform', 'sim-league-toolkit')}</th>
                    <td>{server.platform}</td>
                </tr>
                <tr>
                    <th scope='row' style={{minWidth: '100px'}}>{__('Is Hosted', 'sim-league-toolkit')}</th>
                    <td>{server.isHostedServer ? __('Yes', 'sim-league-toolkit') : __('No', 'sim-league-toolkit')}</td>
                </tr>
                </tbody>
            </table>
        </Card>
    )
}