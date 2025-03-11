import React from "react";
import "./rating.css"

const PrecisionRating = ({rating, large}) => {

    const width = rating / 0.05;
    return (
        <div
            className={`rating ${large ? 'rating--large' : ''}`}
            itemType="http://schema.org/AggregateRating"
            itemScope=""
            itemProp="aggregateRating">
            <div className="rating__body">
                <div className="rating__active" style={{width: `${width}%`}}> </div>
            </div>
        </div>
    );
}

export default PrecisionRating;

