/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from "@wordpress/i18n";

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { InspectorControls, useBlockProps } from "@wordpress/block-editor";
import { PanelBody, TextControl, ToggleControl } from "@wordpress/components";
/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import "./editor.scss";

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */

import { sanitizeTextField } from "./../../js/utils.js";

export default function Edit({ attributes, setAttributes }) {
	const { productName, productPrice } = attributes;

	return (
		<>
			<div {...useBlockProps()}>
				<div className="text-control-two-container">
					<div className="text-control-wrapper">
						<TextControl
							className="productTag"
							label={__("Product name", "wp-ultra-simple-paypal-shopping-cart")}
							value={productName || ""}
							onChange={(value) =>
								setAttributes({ productName: sanitizeTextField(value) })
							}
							style={{ marginRight: "10px" }} // Add some space between the controls
						/>
					</div>
					<div className="text-control-wrapper">
						<TextControl
							className="productTag"
							label={__(
								"Product price",
								"wp-ultra-simple-paypal-shopping-cart",
							)}
							value={productPrice || ""}
							onChange={(value) =>
								setAttributes({ productPrice: sanitizeTextField(value) })
							}
						/>
					</div>
				</div>
			</div>
		</>
	);
}
