import React from 'react';

import './Button.scss';
const Button = ({type, content, link}) => {
    return (
        <div className={`button button-${type}`}>
            <a href={link}>{content}</a>
        </div>

    )
}

export default Button;
