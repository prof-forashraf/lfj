import React from 'react';
import { Link } from 'react-router-dom';

const Breadcrumbs = ({ crumbs = [] }) => {
  if (!crumbs.length) return null;

  return (
    <nav aria-label="Breadcrumb" className="mb-6 text-sm text-gray-600">
      <ol className="flex flex-wrap items-center gap-2">
        {crumbs.map((crumb, index) => {
          const isLast = index === crumbs.length - 1;

          return (
            <li key={`${crumb.href}-${index}`} className="flex items-center gap-2">
              {index > 0 ? <span aria-hidden="true" className="text-gray-400">/</span> : null}
              {isLast ? (
                <span aria-current="page" className="font-medium text-gray-900">{crumb.label}</span>
              ) : (
                <Link to={crumb.href} className="hover:text-primary-gold">{crumb.label}</Link>
              )}
            </li>
          );
        })}
      </ol>
    </nav>
  );
};

export default Breadcrumbs;
