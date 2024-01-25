import React, {useContext, useEffect, useState} from 'react';

export default function Pagination({ page, lastPage, setPage }) {

    const first = Math.max(page - 4 ,1);
    const end = Math.min(page + 4, lastPage);
    const pages= [];

    for (let i = first; i <= end; i++) {
        pages.push(i);
    }

    return (
        <div className="pagination">
            { page > 1 &&
                <span className="page" onClick={() => setPage(page - 1)}>
                -
                </span>}

            { pages.map(i => <span key={i} className={`page ${page === i ? 'active' : ''}`} onClick={page === i ? undefined : () => setPage(i)}>{i}</span>)}


            {page < lastPage &&
                <span className="page" onClick={() => setPage(page + 1)}>
                    >
                </span>
            }
        </div>
    );
}
