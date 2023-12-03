import React from 'react';

import './Card.scss';

const CardImage = ({imageUrl}) => {
  return (
    <div className="card__image">
      <img src={imageUrl} alt="contact image" className="image"/>
    </div>
  );
}

export default CardImage;
