import React from "react";

import "./Card.scss";

const CardField = ({label, value}) => {
    return (
        <div className="card__field">
            <span className="property">{label}</span>
            <span className="value">{value}</span>
        </div>
    );
}

export default CardField;