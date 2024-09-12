import domReady from '@wordpress/dom-ready';
import {createRoot, useEffect, useState} from '@wordpress/element';
import {__} from '@wordpress/i18n';
import apiFetch from "@wordpress/api-fetch";
import {Button, Panel, PanelBody} from "@wordpress/components";

const ServersPage = () => {

  const [servers, setServers] = useState([]);
  useEffect(() => {
    async function loadServers() {
      const response = await apiFetch(
        {
          path: 'sltk/v1/server',
          method: 'GET'
        }
      );

      setServers(response);
    }

    loadServers();
  });

  const handleEditServer = (server) => {
    console.log(server);
  }

  return (
    <div className='wrap'>
      <h2>{__('Servers', 'sim-league-toolkit')} </h2>
      <p>
        {__('A Server in Sim League Toolkit represents the configuration settings for the game server either hosted or local where you run your events.  ', 'sim-league-toolkit')}
      </p>
      <p>
        {__('Although creating championships and events is possible before creating any servers, you will need to create one and select it before generating the configuration files for an event.  ', 'sim-league-toolkit')}
      </p>
      <p>
        {__('Click the Add button below to create a new server.', 'sim-league-toolkit')}
      </p>
      <Panel header='Servers'>
        <PanelBody>
          <table className='admin-table'>
            <thead>
            <tr>
              <th>{__('Name', 'sim-league-toolkit')}</th>
              <th>{__('Game', 'sim-league-toolkit')}</th>
              <th>{__('Platform', 'sim-league-toolkit')}</th>
              <th>{__('Hosted', 'sim-league-toolkit')}</th>
              <th></th>
            </tr>
            </thead>
            <tbody>
            {servers.map((server) => {
              return (<tr key={server.id}>
                <td>{server.name}</td>
                <td>{server.game}</td>
                <td>{server.platform}</td>
                <td>{server.isHostedServer}</td>
                <td><Button onClick={() => handleEditServer(server)} icon='edit'
                            variant='secondary' size='small'
                            tooltip={__('Edit the details of this server.', 'sim-league-toolkit')} /></td>
              </tr>)
            })}
            </tbody>
          </table>
        </PanelBody>
      </Panel>
    </div>
  )
}

domReady(() => {
  const root = createRoot(
    document.getElementById('servers-root'),
  );

  root.render(<ServersPage />);
});