import {__} from '@wordpress/i18n';

import {Button} from 'primereact/button';
import {Card} from 'primereact/card';

import {StandaloneEventClass} from '../../../features/standaloneEvent';

interface StandaloneEventClassCardProps {
    item: StandaloneEventClass;
    onRequestDelete: (item: StandaloneEventClass) => void;
}

export const StandaloneEventClassCard = ({item, onRequestDelete}: StandaloneEventClassCardProps) => {
    const footer = (
        <>
            {!item.isInUse && (
                <Button label={__('Remove', 'sim-league-toolkit')} icon='pi pi-times' severity='danger'
                        onClick={() => onRequestDelete(item)} style={{marginLeft: '1rem'}}/>)
            }
        </>
    );
    return (
        <>
            <Card title={item.name} subTitle={item.game}
                  footer={footer}
                  style={{margin: '1rem', maxWidth: '400px'}}>
                <table className='table-no-border'>
                    <tbody>
                    <tr>
                        <th scope='row'>{__('Car Class', 'sim-league-toolkit')}</th>
                        <td>{item.carClass}</td>
                    </tr>
                    <tr>
                        <th scope='row'>{__('Driver Category', 'sim-league-toolkit')}</th>
                        <td>{item.driverCategory}</td>
                    </tr>
                    <tr>
                        <th scope='row'>{__('Is Single Car Class', 'sim-league-toolkit')}</th>
                        <td>{item.isSingleCarClass ? __('Yes', 'sim-league-toolkit') : __('No', 'sim-league-toolkit')}</td>
                    </tr>
                    {item.isSingleCarClass &&
                        <tr>
                            <th scope='row'>{__('Car', 'sim-league-toolkit')}</th>
                            <td>{item.singleCarName}</td>
                        </tr>
                    }
                    <tr>
                        <th scope='row'>{__('Is In Use', 'sim-league-toolkit')}</th>
                        <td>{item.isInUse ? __('Yes', 'sim-league-toolkit') : __('No', 'sim-league-toolkit')}</td>
                    </tr>
                    </tbody>
                </table>
            </Card>
        </>
    );
};
