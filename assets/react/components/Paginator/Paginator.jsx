import React from 'react';

import "./Paginator.scss";

const Paginator = ({currentPage, totalPages, onPageChange}) => {
    const renderPageNumbers = () => {
        const pageNumbers = [];
        let startPage;

        if (currentPage === 0) {
            startPage = 0;
        } else if (currentPage === totalPages - 1) {
            startPage = Math.max(0, totalPages - 5);
        } else {
            startPage = Math.max(0, currentPage - 2);
        }

        const endPage = Math.min(startPage + 4, totalPages - 1);

        for (let i = startPage; i <= endPage; i++) {
            pageNumbers.push(
                <span
                    key={i}
                    onClick={() => onPageChange(i)}
                    className={currentPage === i ? 'paginator-control active-page' : 'paginator-control'}
                >
          {i + 1}
        </span>
            );
        }

        return pageNumbers;
    };

    return (
        <div className="paginator">
            <a className="paginator-control" onClick={() => onPageChange(0)}>&lt;&lt;</a>
            <a className="paginator-control" onClick={() => onPageChange(Math.max(currentPage - 1, 0))}>&lt;</a>
            {renderPageNumbers()}
            <a className="paginator-control"
               onClick={() => onPageChange(Math.min(currentPage + 1, totalPages - 1))}>&gt;</a>
            <a className="paginator-control" onClick={() => onPageChange(totalPages - 1)}>&gt;&gt;</a>
        </div>
    );
};

export default Paginator;
