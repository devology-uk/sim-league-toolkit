import {__} from '@wordpress/i18n';

import {Button} from 'primereact/button';
import {Card} from 'primereact/card';

import {ChampionshipClass} from "../../types/ChampionshipClass";

interface ChampionshipClassCardProps {
    championshipClass: ChampionshipClass;
    onRequestDelete: (item: ChampionshipClass) => void;
}

export const ChampionshipClassCard = ({championshipClass, onRequestDelete}: ChampionshipClassCardProps) => {
    const footer = (
        <>
            {!championshipClass.isInUse && (
                <Button label={__('Remove', 'sim-league-toolkit')} icon='pi pi-times' severity='danger'
                        onClick={() => onRequestDelete(championshipClass)} style={{marginLeft: '1rem'}}/>)
            }
        </>
    );
    return (
        <>
            <Card title={championshipClass.name} subTitle={championshipClass.game}
                  footer={footer}
                  style={{margin: '1rem', maxWidth: '400px'}}>
                <table className='table-no-border'>
                    <tbody>
                    <tr>
                        <th scope='row'>{__('Car Class', 'sim-league-toolkit')}</th>
                        <td>{championshipClass.carClass}</td>
                    </tr>
                    <tr>
                        <th scope='row'>{__('Driver Category', 'sim-league-toolkit')}</th>
                        <td>{championshipClass.driverCategory}</td>
                    </tr>
                    <tr>
                        <th scope='row'>{__('Is Single Car Class', 'sim-league-toolkit')}</th>
                        <td>{championshipClass.isSingleCarClass ? __('Yes', 'sim-league-toolkit') : __('No', 'sim-league-toolkit')}</td>
                    </tr>
                    {championshipClass.isSingleCarClass &&
                        <tr>
                            <th scope='row'>{__('Car', 'sim-league-toolkit')}</th>
                            <td>{championshipClass.singleCarName}</td>
                        </tr>
                    }
                    <tr>
                        <th scope='row'>{__('Is In Use', 'sim-league-toolkit')}</th>
                        <td>{championshipClass.isInUse ? __('Yes', 'sim-league-toolkit') : __('No', 'sim-league-toolkit')}</td>
                    </tr>
                    </tbody>
                </table>
            </Card>
        </>
    );
};