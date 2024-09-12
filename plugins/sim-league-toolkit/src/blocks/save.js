import {useBlockProps} from "@wordpress/block-editor"
import './editor.scss'

export default function Save({attributes, setAttributes}) {
  const blockProps = useBlockProps()

  return (
    <div {...blockProps}>
      <div className='placeholder-block'>
        Placeholder
        <p>
          This block is no longer available. The site administrator may have removed a plugin or switched themes.
        </p>
      </div>
    </div>
  )
}