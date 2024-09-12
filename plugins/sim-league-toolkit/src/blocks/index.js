import {registerBlockType} from '@wordpress/blocks'
import metadata from './block.json'
import Edit from './edit'
import './main.scss'


registerBlockType(metadata.name, {
  edit: Edit
})

