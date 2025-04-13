import React from 'react';
import PropTypes from 'prop-types';
import './ProductCard.css';

const ProductCard = ({
  image,
  title,
  price,
  description,
  ctaText,
  ctaIcon,
  badge,
  badgeColor,
  layout,
  alignment,
  spacing,
  shadow,
  border,
  background,
  textColor,
  animation,
  hoverEffect,
  transition,
}) => {
  const classes = [
    'product-card',
    `product-card--${layout}`,
    `product-card--${alignment}`,
    `product-card--${spacing}`,
    `product-card--${shadow}`,
    `product-card--${border}`,
    `product-card--${background}`,
    `product-card--${textColor}`,
    `product-card--${animation}`,
    `product-card--${hoverEffect}`,
    `product-card--${transition}`,
  ];

  return (
    <div className={classes.join(' ')}>
      {badge && (
        <div className={`product-card__badge product-card__badge--${badgeColor}`}>
          {badge}
        </div>
      )}

      <div className="product-card__image">
        {image && (
          <img
            src={image.url}
            alt={image.alt}
            width={image.width}
            height={image.height}
          />
        )}
      </div>

      <div className="product-card__content">
        {title && <h3 className="product-card__title">{title}</h3>}
        {description && (
          <div className="product-card__description">{description}</div>
        )}
        {price && (
          <div className="product-card__price">
            {price.amount} {price.currencyCode}
          </div>
        )}
        <button className="product-card__cta">
          {ctaIcon && (
            <span className="product-card__cta-icon">{ctaIcon}</span>
          )}
          {ctaText}
        </button>
      </div>
    </div>
  );
};

ProductCard.propTypes = {
  image: PropTypes.shape({
    url: PropTypes.string,
    alt: PropTypes.string,
    width: PropTypes.number,
    height: PropTypes.number,
  }),
  title: PropTypes.string,
  price: PropTypes.shape({
    amount: PropTypes.number,
    currencyCode: PropTypes.string,
  }),
  description: PropTypes.string,
  ctaText: PropTypes.string,
  ctaIcon: PropTypes.string,
  badge: PropTypes.string,
  badgeColor: PropTypes.string,
  layout: PropTypes.string,
  alignment: PropTypes.string,
  spacing: PropTypes.string,
  shadow: PropTypes.string,
  border: PropTypes.string,
  background: PropTypes.string,
  textColor: PropTypes.string,
  animation: PropTypes.string,
  hoverEffect: PropTypes.string,
  transition: PropTypes.string,
};

export default ProductCard;
