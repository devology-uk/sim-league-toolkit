import {__} from '@wordpress/i18n';

import {Button} from 'primereact/button';
import {Card} from 'primereact/card';
import {Championship} from '../../types/Championship';
import {ChampionshipType} from '../../types/generated/ChampionshipType';

interface ChampionshipCardProps {
    championship: Championship;
    onRequestEdit: (championship: Championship) => void;
    onRequestDelete: (championship: Championship) => void;
}

export const ChampionshipCard = ({championship, onRequestEdit, onRequestDelete}: ChampionshipCardProps) => {

    let startDateTime: Date = new Date(championship.startDate);
    const startDate = startDateTime.toLocaleDateString();

    const footer = (
        <>
            <Button label={__('Edit', 'sim-league-toolkit')} icon='pi pi-pencil'
                    onClick={() => onRequestEdit(championship)}/>
            <Button label={__('Delete', 'sim-league-toolkit')} icon='pi pi-times' severity='danger'
                    onClick={() => onRequestDelete(championship)} style={{marginLeft: '1rem'}}/>
        </>
    );
    const header = (
        <img alt={__('Banner image', 'sim-league-toolkit')}
             src={championship.bannerImageUrl} height='168px'/>
    );

    return (
        <Card title={championship.name} subTitle={championship.game}
              footer={footer} header={header}
              style={{margin: '1rem', maxWidth: '400px'}}>
            <table className='table-no-border'>
                <tbody>
                <tr>
                    <th scope='row'>{__('Platform', 'sim-league-toolkit')}</th>
                    <td>{championship.platform}</td>
                </tr>
                <tr>
                    <th scope='row'>{__('Start Date', 'sim-league-toolkit')}</th>
                    <td>{startDate}</td>
                </tr>
                <tr>
                    <th scope='row'>{__('Type', 'sim-league-toolkit')}</th>
                    <td>{championship.championshipType === ChampionshipType.TRACK_MASTER ? __('Track Master', 'sim-league-toolkit') : __('Standard',
                                                                                                       'sim-league-toolkit')}</td>
                </tr>
                {championship.championshipType === ChampionshipType.TRACK_MASTER && (
                    <tr>
                        <th scope='row'>{__('Track', 'sim-league-toolkit')}</th>
                        <td>{championship.trackMasterTrack}</td>
                    </tr>
                )
                }

                {(championship.trackMasterTrackLayoutId ?? 0 > 0) && (
                    <tr>
                        <th scope='row'>{__('Track Layout', 'sim-league-toolkit')}</th>
                        <td>{championship.trackMasterTrackLayout}</td>
                    </tr>
                )}
                <tr>
                    <th scope='row'>{__('Completed', 'sim-league-toolkit')}</th>
                    <td>{championship.trophiesAwarded ? __('Yes', 'sim-league-toolkit') : __('No',
                                                                                             'sim-league-toolkit')}</td>
                </tr>
                </tbody>
            </table>
        </Card>
    );
};