( function() {

    var el            = wp.element.createElement;
    var Fragment      = wp.element.Fragment;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var useBlockProps = wp.blockEditor.useBlockProps;
    var __            = wp.i18n.__;

    var PanelBody     = wp.components.PanelBody;
    var TextControl   = wp.components.TextControl;
    var SelectControl = wp.components.SelectControl;
    var ToggleControl = wp.components.ToggleControl;
    var RangeControl  = wp.components.RangeControl;

    // Badge helper
    function Badge( label, value, color ) {
        var bg = color || '#e8f4fd';
        var tc = '#1a5276';
        return el( 'span', {
            style: {
                display: 'inline-block',
                background: bg,
                color: tc,
                borderRadius: '20px',
                padding: '4px 12px',
                fontSize: '12px',
                fontWeight: '500',
                margin: '3px 4px',
                border: '1px solid rgba(0,0,0,0.08)'
            }
        }, label + ': ' + value );
    }

    wp.blocks.registerBlockType( 'i13/vertical-news-scroller-free', {

        title:       __( 'News Scroller', 'vertical-news-scroller' ),
        description: __( 'Display a vertical scrolling news ticker.', 'vertical-news-scroller' ),
        category:    'widgets',
        icon:        'rss',
        supports:    { html: false },

        attributes: {
            s_type:                { type: 'string',  default: 'modern' },
            lib:                   { type: 'string',  default: 'v3' },
            style_preset:          { type: 'string',  default: 'default' },
            maxitem:               { type: 'number',  default: 5 },
            height:                { type: 'number',  default: 200 },
            width:                 { type: 'string',  default: '100%' },
            direction:             { type: 'string',  default: 'up' },
            padding:               { type: 'number',  default: 10 },
            show_content:          { type: 'boolean', default: true },
            add_link_to_title:     { type: 'boolean', default: true },
            modern_scroller_delay: { type: 'number',  default: 5000 },
            modern_speed:          { type: 'number',  default: 1700 },
            delay:                 { type: 'number',  default: 60 },
            scrollamount:          { type: 'number',  default: 1 }
        },

        edit: function( props ) {
            var attr      = props.attributes;
            var setAttr   = props.setAttributes;
            var blockProps = useBlockProps();

            var on  = '#d5f5e3';
            var off = '#fde8e8';
            var neu = '#eaf2ff';

            return el( Fragment, null,

                // ── Inspector Panel ──────────────────────────────────────
                el( InspectorControls, null,

                    el( PanelBody, { title: __( 'Content', 'vertical-news-scroller' ), initialOpen: true },
                        el( RangeControl, {
                            label: __( 'Max Items', 'vertical-news-scroller' ),
                            value: attr.maxitem, min: 1, max: 50,
                            onChange: function(v){ setAttr({ maxitem: v }); }
                        })
                    ),

                    el( PanelBody, { title: __( 'Display', 'vertical-news-scroller' ), initialOpen: false },
                        el( ToggleControl, { label: __( 'Show Content/Excerpt', 'vertical-news-scroller' ), checked: attr.show_content, onChange: function(v){ setAttr({ show_content: v }); } }),
                        el( ToggleControl, { label: __( 'Link Title to News URL', 'vertical-news-scroller' ), checked: attr.add_link_to_title, onChange: function(v){ setAttr({ add_link_to_title: v }); } })
                    ),

                    el( PanelBody, { title: __( 'Style', 'vertical-news-scroller' ), initialOpen: false },
                        el( SelectControl, {
                            label: __( 'Visual Style', 'vertical-news-scroller' ),
                            value: attr.style_preset,
                            options: [ { label: 'Default (original look)', value: 'default' }, { label: 'Card (boxed, shadow)', value: 'card' } ],
                            onChange: function(v){ setAttr({ style_preset: v }); }
                        })
                    ),

                    el( PanelBody, { title: __( 'Layout', 'vertical-news-scroller' ), initialOpen: false },
                        el( RangeControl, { label: __( 'Height (px)', 'vertical-news-scroller' ), value: attr.height, min: 50, max: 800, onChange: function(v){ setAttr({ height: v }); } }),
                        el( TextControl,  { label: __( 'Width (px or %)', 'vertical-news-scroller' ), value: attr.width, onChange: function(v){ setAttr({ width: v }); } }),
                        el( RangeControl, { label: __( 'Padding (px)', 'vertical-news-scroller' ), value: attr.padding, min: 0, max: 40, onChange: function(v){ setAttr({ padding: v }); } })
                    ),

                    el( PanelBody, { title: __( 'Scroller', 'vertical-news-scroller' ), initialOpen: false },
                        el( SelectControl, {
                            label: __( 'Scroller Type', 'vertical-news-scroller' ),
                            value: attr.s_type,
                            options: [ { label: 'Modern (smooth slide)', value: 'modern' }, { label: 'Classic (continuous scroll)', value: 'classic' } ],
                            onChange: function(v){ setAttr({ s_type: v }); }
                        }),
                        el( SelectControl, {
                            label: __( 'JS Library', 'vertical-news-scroller' ),
                            value: attr.lib,
                            options: [ { label: 'V1 (vTicker)', value: 'v1' }, { label: 'V2 (vTickerV2)', value: 'v2' }, { label: 'V3 (Recommended)', value: 'v3' } ],
                            onChange: function(v){ setAttr({ lib: v }); }
                        }),
                        el( SelectControl, {
                            label: __( 'Direction', 'vertical-news-scroller' ),
                            value: attr.direction,
                            options: [ { label: 'Up', value: 'up' }, { label: 'Down', value: 'down' } ],
                            onChange: function(v){ setAttr({ direction: v }); }
                        }),
                        attr.s_type === 'modern'
                            ? el( Fragment, null,
                                el( RangeControl, { label: __( 'Pause Between Items (ms)', 'vertical-news-scroller' ), value: attr.modern_scroller_delay, min: 500, max: 20000, step: 500, onChange: function(v){ setAttr({ modern_scroller_delay: v }); } }),
                                el( RangeControl, { label: __( 'Animation Speed (ms)', 'vertical-news-scroller' ), value: attr.modern_speed, min: 200, max: 5000, step: 100, onChange: function(v){ setAttr({ modern_speed: v }); } })
                            )
                            : el( Fragment, null,
                                el( RangeControl, { label: __( 'Scroll Delay (ms)', 'vertical-news-scroller' ), value: attr.delay, min: 1, max: 500, onChange: function(v){ setAttr({ delay: v }); } }),
                                el( RangeControl, { label: __( 'Scroll Amount (px)', 'vertical-news-scroller' ), value: attr.scrollamount, min: 1, max: 20, onChange: function(v){ setAttr({ scrollamount: v }); } })
                            )
                    )
                ),

                // ── Block Preview Card ───────────────────────────────────
                el( 'div', blockProps,
                    el( 'div', {
                        style: {
                            border: '1px dashed #b2bec3',
                            borderRadius: '8px',
                            padding: '20px 16px',
                            textAlign: 'center',
                            background: '#fafafa'
                        }
                    },
                        // Icon + Title
                        el( 'div', { style: { fontSize: '32px', marginBottom: '4px' } }, '📰' ),
                        el( 'strong', { style: { fontSize: '15px', display: 'block', marginBottom: '12px', color: '#2d3436' } },
                            __( 'News Scroller', 'vertical-news-scroller' )
                        ),

                        // Row 1 — Items, Type, Library
                        el( 'div', null,
                            Badge( 'Items', attr.maxitem, neu ),
                            Badge( 'Type', attr.s_type, neu ),
                            Badge( 'Lib', attr.lib.toUpperCase(), neu ),
                            Badge( 'Style', attr.style_preset, neu )
                        ),

                        // Row 2 — Direction, Height
                        el( 'div', null,
                            Badge( 'Direction', attr.direction, neu ),
                            Badge( 'Height', attr.height + 'px', neu )
                        ),

                        // Row 3 — toggles
                        el( 'div', null,
                            Badge( 'Content', attr.show_content  ? 'On' : 'Off', attr.show_content  ? on : off ),
                            Badge( 'Links',   attr.add_link_to_title ? 'On' : 'Off', attr.add_link_to_title ? on : off )
                        ),

                        el( 'p', { style: { fontSize: '11px', color: '#999', marginTop: '12px', marginBottom: '4px' } },
                            __( 'Scroller renders on the frontend. Use the sidebar to configure settings.', 'vertical-news-scroller' )
                        ),
                        el( 'p', { style: { fontSize: '11px', color: '#8224e3', marginTop: '6px', marginBottom: 0 } },
                            __( 'Want categories, RSS feeds, visual styles, and a lightbox preview?', 'vertical-news-scroller' ) + ' ',
                            el( 'a', { href: 'https://www.i13websolution.com/product/wordpress-vertical-news-scroller-pro/', target: '_blank', style: { color: '#8224e3', fontWeight: '600', textDecoration: 'none' } },
                                __( 'See Pro', 'vertical-news-scroller' ) + ' →'
                            )
                        )
                    )
                )
            );
        },

        save: function() { return null; }
    });

} )();
