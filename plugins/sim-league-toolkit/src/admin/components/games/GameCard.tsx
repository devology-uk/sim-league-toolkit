import {__} from '@wordpress/i18n';

import {Card} from 'primereact/card';
import {Game} from "./Game";

interface GameCardProps {
    game: Game;
}

export const GameCard = ({game}: GameCardProps) => {
    return (
        <Card title={game.name}
              style={{margin: '1rem', maxWidth: '400px'}}>
            <table className='table-no-border'>
                <tbody>
                <tr>
                    <th scope='row'>{__('Latest Version Supported', 'sim-league-toolkit')}</th>
                    <td>{game.latestVersion}</td>
                </tr>
                <tr>
                    <th scope='row'>{__('Supports Result Upload', 'sim-league-toolkit')}</th>
                    <td>{game.supportsResultUpload ? __('Yes', 'sim-league-toolkit') : __('No', 'sim-league-toolkit')}</td>
                </tr>
                <tr>
                    <th scope='row'>{__('Supports Layouts', 'sim-league-toolkit')}</th>
                    <td>{game.supportsLayouts ? __('Yes', 'sim-league-toolkit') : __('No', 'sim-league-toolkit')}</td>
                </tr>
                <tr>
                    <th scope='row'>{__('Status', 'sim-league-toolkit')}</th>
                    <td>{game.published ? __('Published', 'sim-league-toolkit') : __('Planned', 'sim-league-toolkit')}</td>
                </tr>
                </tbody>
            </table>
        </Card>
    )
}