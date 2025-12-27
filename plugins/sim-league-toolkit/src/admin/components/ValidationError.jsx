export const ValidationError = ({message, show}) => {
    return (
        <>
            {show && <div className='validation-error'>{message}</div>}
        </>);
}