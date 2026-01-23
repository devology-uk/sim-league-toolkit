import {Button} from 'primereact/button';
import {__} from '@wordpress/i18n';
import {Card} from 'primereact/card';
import {ScoringSet} from "../../types/ScoringSet";

interface ScoringSetCardProps {
    scoringSet: ScoringSet;
    onRequestEdit: (item: ScoringSet) => void;
    onRequestDelete: (item: ScoringSet) => void;
}

export const ScoringSetCard = ({scoringSet, onRequestEdit, onRequestDelete}: ScoringSetCardProps) => {

    const footer = (
        <>
            {!scoringSet.isBuiltIn && (
                <Button label={__('Edit', 'sim-league-toolkit')} icon='pi pi-pencil'
                        onClick={() => onRequestEdit(scoringSet)}/>
            )}
            {!scoringSet.isBuiltIn && !scoringSet.isInUse && (
                <Button label={__('Delete', 'sim-league-toolkit')} icon='pi pi-times' severity='danger'
                        onClick={() => onRequestDelete(scoringSet)} style={{marginLeft: '1rem'}}/>
            )}
        </>
    );

    return (
        <Card title={scoringSet.name} footer={footer} style={{margin: '1rem', maxWidth: '300px'}}>
            <p>{scoringSet.description}</p>
            <table className='table-no-border'>
                <tbody>
                <tr>
                    <th scope='row'>{__('Points for Fastest Lap', 'sim-league-toolkit')}</th>
                    <td>{scoringSet.pointsForFastestLap}</td>
                </tr>
                <tr>
                    <th scope='row'>{__('Points for Pole', 'sim-league-toolkit')}</th>
                    <td>{scoringSet.pointsForPole}</td>
                </tr>
                <tr>
                    <th scope='row'>{__('Points for Finishing', 'sim-league-toolkit')}</th>
                    <td>{scoringSet.pointsForFinishing}</td>
                </tr>
                <tr>
                    <th scope='row'>{__('Is Built In', 'sim-league-toolkit')}</th>
                    <td>{scoringSet.isBuiltIn ? __('Yes', 'sim-league-toolkit') : __('No', 'sim-league-toolkit')}</td>
                </tr>
                <tr>
                    <th scope='row'>{__('Is In Use', 'sim-league-toolkit')}</th>
                    <td>{scoringSet.isInUse ? __('Yes', 'sim-league-toolkit') : __('No', 'sim-league-toolkit')}</td>
                </tr>
                </tbody>
            </table>
        </Card>
    )
}