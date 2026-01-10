interface ValidationErrorProps {
    message: string
    show: boolean
}

export const ValidationError = ({message, show}: ValidationErrorProps) => {
    return (
        <>
            {show && <div className='validation-error'>{message}</div>}
        </>);
}