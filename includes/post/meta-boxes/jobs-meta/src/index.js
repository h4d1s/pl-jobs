import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { useEntityProp } from '@wordpress/core-data';
import { date } from '@wordpress/date';
import { DateTimePicker, Dropdown, Button, PanelRow, FormToggle } from '@wordpress/components';
import metadata from './block.json';

const JobMetaPluginPanel = () => {
	const postType = useSelect(
		( select ) => select( 'core/editor' ).getCurrentPostType(),
		[]
	);
	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	const positionFilledMetaFieldValue = meta[ '_pl_jobs_post_position_filled' ];
	const updatePositionFilledMetaValue = ( newValue ) => {
		setMeta( { ...meta, _pl_jobs_post_position_filled: newValue } );
	};

	const closingDateMetaFieldValue = meta[ '_pl_jobs_post_closing_date' ];
	const updateClosingDateMetaValue = ( newValue ) => {
		setMeta( { ...meta, _pl_jobs_post_closing_date: newValue } );
	};

	return (
		<PluginDocumentSettingPanel
			name="job-meta-panel"
			title="Job Meta"
			className="job-meta-panel"
		>
			<PanelRow>
				<span>{ __("Closing date", "pixel-labs") }</span>
				<Dropdown
					position="middle left"
					renderToggle={ (({ isOpen, onToggle }) => (
						<Button variant="link" onClick={ onToggle } aria-expanded={ isOpen }>
							{ closingDateMetaFieldValue ? date( 'd.m.Y', closingDateMetaFieldValue ) : __("Click to set the date", "pixel-labs") }
						</Button>
					)) }
					renderContent={ () => (
						<div>
							<DateTimePicker
								currentDate={ closingDateMetaFieldValue }
								onChange={ newDate => updateClosingDateMetaValue(date('Y-m-d', newDate)) }
							/>
							<Button onClick={ () => updateClosingDateMetaValue(null) } variant="primary">
								{ __("Clear", "pixel-labs") }
							</Button>
						</div>
					) }>
				</Dropdown>
			</PanelRow>

			<PanelRow>
				<span>{ __("Position filled", "pixel-labs") }</span>
				<FormToggle
					checked={ positionFilledMetaFieldValue }
					onChange={ () => updatePositionFilledMetaValue(!positionFilledMetaFieldValue) }
				/>
			</PanelRow>
		</PluginDocumentSettingPanel>
	);
};

registerPlugin( metadata.name, {
    render: JobMetaPluginPanel,
    icon: metadata.icon,
} );