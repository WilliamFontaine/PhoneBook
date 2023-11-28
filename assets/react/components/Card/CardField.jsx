import React from "react";

import "./Card.scss";

const CardField = ({label, value, customClass}) => {
    return (
        <div className="card__field">
            <span className="property">{label}</span>
            <span className={`value ${customClass ? customClass : ""}`}>{value}</span>
        </div>
    );
}

export default CardField;