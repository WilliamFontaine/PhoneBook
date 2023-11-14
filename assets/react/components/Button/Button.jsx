import React from 'react';
const Button = ({type, content, id}) => {
    return (
        <div className="button">
            {id !== ""
                ? <a className={type} href={`/contacts/${id}`}>{content}</a>
                : <a className={type} href={`/contacts/new`}>{content}</a>
            }
        </div>

    )
}

export default Button;
