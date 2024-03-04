import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { __ } from '@wordpress/i18n';
import { PanelRow } from '@wordpress/components';
import metadata from './block.json';

const JobStatusPluginPanel = () => {
	const data = window.jobs_status_block_data;

	return (
		<PluginDocumentSettingPanel
			name="job-status-panel"
			title="Job Status"
			className="job-status-panel"
		>
			<PanelRow>
				<span>{ __("Views", "pixel-labs") }</span>
				<span>{ data.post_views }</span>
			</PanelRow>

			<PanelRow>
				<span>{ __("Applications", "pixel-labs") }</span>
				<span>{ data.applicants_count }</span>
			</PanelRow>

			<PanelRow>
				<span>{ __("Last submission", "pixel-labs") }</span>
				<span>{ data.application_date }</span>
			</PanelRow>
		</PluginDocumentSettingPanel>
	);
};

registerPlugin( metadata.name, {
    render: JobStatusPluginPanel,
    icon: metadata.icon,
} );