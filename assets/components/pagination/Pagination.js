import React from 'react';
import LeftArrowIcon from "../icons/LeftArrowIcon";
import RightArrowIcon from "../icons/RightArrowIcon";
import './pagination.scss';

export default function Pagination({ page, lastPage, setPage }) {

    const first = Math.max(page - 4 , 1);
    const end = Math.min(page + 4, lastPage);
    const pages= [];

    for (let i = first; i <= end; i++) {
        pages.push(i);
    }

    return (
        <div className="pagination">
            {lastPage > 1 &&
                <span className="pagination__page arrow" onClick={() => page === 1 ? undefined : setPage(page - 1)}>
                    <LeftArrowIcon/>
                </span>
            }

            {pages.map(i => <span key={i} className={`pagination__page ${page === i ? 'pagination__page-active' : ''}`}
                                  onClick={page === i ? undefined : () => setPage(i)}>{i}</span>)}

            <span className="pagination__total">
                из <span className="pagination__page" onClick={() => setPage(lastPage)}>{lastPage}</span>
            </span>

            {page < lastPage &&
                <span className="pagination__page arrow" onClick={() => setPage(page + 1)}>
                    <RightArrowIcon/>
                </span>
            }
        </div>
    );
}
