import {useBlockProps} from "@wordpress/block-editor"
import './editor.scss'

export default function Edit({attributes, setAttributes}) {
  const blockProps = useBlockProps()

  return (
    <div {...blockProps}>
      <div className='placeholder-block'>Placeholder</div>
    </div>
  )
}