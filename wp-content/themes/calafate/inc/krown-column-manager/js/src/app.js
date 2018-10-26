import React from 'react';
import ReactDOM from 'react-dom';
import autobind from 'autobind-decorator';

let cookies = require('browser-cookies');
let $ = jQuery;

@autobind
class App extends React.Component {

	/* ------ REACT STUFF -----
		----
			-- */

	constructor() {

		super();

		this.state = {
			content: []
		}

		this.colIndex = 0;

	}

	// Initial rendering (init everything)

	componentDidMount() {

		// init everything (we need to check for tinymce or plain content)

		if ( $('#wp-content-wrap').hasClass('html-active') ) {
			setTimeout(() => {
				window.switchEditors.go('content', 'tmce');
			}, 1000);
		} 

		let initI = setInterval(() => {
			if ( window.tinymce && window.tinymce.get('content') ) {
				this.initApp();
				clearInterval(initI);
			}
		}, 100);

		// -- init buttons

		// new column

		$('.kcmp-add-column').on('click', (function(e) {
			this.addNewColumn();
			e.preventDefault();
		}).bind(this));

		// popup actions

		$('#kcmp-edit').on('click', (function(e) {
			this.editColumnContent();
			e.preventDefault();
		}).bind(this));

		$('#kcmp-delete').on('click', (function(e) {
			this.removeColumn($('#kcmp').data('id'), e);
			$('#kcmp').removeClass('open');
		$('body').removeClass('kill-overflow');
		}).bind(this));

		// popup close

		$('#kcmp-close').on('click', function(e) {
			let ok = confirm(KCMl10n.popupConfirm);
			if ( ok ) {
				$('#kcmp').removeClass('open');
			$('body').removeClass('kill-overflow');
			}
			e.preventDefault();
		});

		// init manager switch buttons

		$('#kcm-switch').on('click', (function(e) {

			if ( $('body').hasClass('kcm-on') ) {

				$('body').removeClass('kcm-on');
				$(e.target).html(KCMl10n.showKCM);
				cookies.set('kcm-switch', 'off', {expires: 365});
				setTimeout(function(){
					$('window').trigger('resize');
				}, 1000);

			} else {

				$('body').addClass('kcm-on');
				$(e.target).html(KCMl10n.showWPE);
				cookies.set('kcm-switch', 'on', {expires: 365});

				if ( e.originalEvent !== undefined ) {
					this.initApp();
				}

			}

			e.preventDefault();

		}).bind(this));

		if ( cookies.get('kcm-switch') === 'on' ) {
			$('#kcm-switch').trigger('click');
		}

		// init carousel

		var $carousel = $('#kcmp').find('.carousel').flickity({
		  cellAlign: 'left',
		  contain: true,
		  prevNextButtons: false,
		  pageDots: false,
		  draggable: false,
		  adaptiveHeight: true,
		  wrapAround: true
		});

		$('#kcmp').find('.device-icon').on('click', function(e){
			var index = $(this).data('index');
			$carousel.flickity('select', index);
			$('#kcmp').find('.device-icon.selected').removeClass('selected');
			$(this).addClass('selected');
			e.preventDefault();
		});

	}

	// Updates WordPress content after each React update

	componentDidUpdate() {

		// Create text/HTML content

		let wpEditorContent = '<div class="grid kcm">';
		let contentState = this.state.content.slice();

		contentState.forEach((column) => {
			wpEditorContent += `<div class="${column.class}">${column.inner}</div>`;
		});

		wpEditorContent += '</div>';

		// Set content 

		if ( $('#wp-content-wrap').hasClass('html-active') ) {
			$('#content.wp-editor-area').val(wpEditorContent);
		} else {
			window.tinymce.get('content').setContent(wpEditorContent);
		}

	}

	/* When sorting happens, we need to first get the new order and 
		change the REACT state, then cancel the jQuery ordering, 
		otherwise we'll have double DOM manipulation. */	

	falseComponentDidUpdate() {	

		let newState = [];

		$('#kcm-grid .grid__item').each((i, column) => {
			newState.push(this.state.content[$(column).data('id')]);
		});
		this.setState({content: newState});

		$('#kcm-grid').sortable('cancel');

	}

	initApp() {

		// build initial state based on wp editor content

		let $initData = $(window.tinymce.get('content').getContent());
		let initState = [];

		if ( $('#content.wp-editor-area').val().length !== 0 ) {

			if ( $initData.hasClass('grid') || $initData.find('.grid').length > 0 ) {

					if ( $initData.find('.grid__item').length > 0 ) {

						// data was created previously with the builder

						$initData.find('.grid__item').each((id, column) => {
							initState.push({
								inner: $(column).html(),
								class: $(column).attr('class'),
								size: this._getColumnWidth($(column))
							})
						});

					} else {

						// data is somehow broken (edge case)

						initState.push({
							inner: $initData.html(),
							class: 'grid__item one-whole portable--auto lap--auto palm--auto',
							size: 12
						});

					}


			} else {

				// data wasn't created with the builder, we need to make a full width column with it

				initState.push({
					inner: $('#content.wp-editor-area').val(),
					class: 'grid__item one-whole portable--auto lap--auto palm--auto',
					size: 12
				});

			}

		}

		// set intial state

		this.setState({
			content: initState
		});

		// init sortable grid

		$('#kcm-grid').sortable({
			update: this.falseComponentDidUpdate
		});

		$('#kcm-grid').disableSelection();

		let zI = 9;

		$('#kcm-grid .grid__item').on('mouseover', (e) => {
			$(e.target).closest('.grid__item').css('zIndex', ++zI);
		});

		$('#krown-column-manager-app').addClass('init');

	}

	/* ------ COLUMN ACTIONS -----
		----
			-- */

	// Adds a new column

	addNewColumn() {

		// open popup

		$('body').addClass('kill-overflow');
		$('#kcmp').addClass('open')
			.removeClass('edit')
			.data('id', 'none');

		$('#wpadminbar').css('zIndex', '-1');

		// reset fields

		if ( $('#wp-kcm_mce-wrap').hasClass('html-active') ) {
			$('#kcm_mce.wp-editor-area').val('');
		} else {
			window.tinymce.get('kcm_mce').setContent('');
		}

		$('#kcmp-width-po select').val('portable--auto');
		$('#kcmp-width-la select').val('lap--auto');
		$('#kcmp-width-pa select').val('palm--auto');
		$('#kcmp-width select').val('three-twelfths');
		$('#kcmp-padding-top select').val('top-no-padding');
		$('#kcmp-padding-bottom select').val('bottom-no-padding');
		$('#kcmp-custom-class').val('');
		$('#kcmp-cc').prop('checked', false);

	}

	// Edits an existing column

	editExistingColumn(id, e) {

		e.preventDefault();

		// open popup

		$('body').addClass('kill-overflow');
		$('#kcmp').addClass('open edit')
			.data('id', id);

		$('#wpadminbar').css('zIndex', '-1');

		// reset fields

		if ( $('#wp-kcm_mce-wrap').hasClass('html-active') ) {
			$('#kcm_mce.wp-editor-area').val(this.state.content[id].inner);
		} else {
			window.tinymce.get('kcm_mce').setContent(this.state.content[id].inner);
		}

		let cclass = '';

		let classes = this.state.content[id].class.split(/\s+/);
		classes.map((name, i) => {
			switch ( i ) {
				case 1:
					if ( name !== '' )
						$('#kcmp-width select').val(name);
					break;
				case 2:
					if ( name !== '' )
						$('#kcmp-width-po select').val(name);
					break;
				case 3:
					if ( name !== '' )
						$('#kcmp-width-la select').val(name);
					break;
				case 4:
					if ( name !== '' )
						$('#kcmp-width-pa select').val(name);
					break;
				case 5:
					if ( name !== '' )
						$('#kcmp-padding-top select').val(name);
					break;
				case 6:
					if ( name !== '' )
						$('#kcmp-padding-bottom select').val(name);
					break;
				default:
					if ( name !== 'center' && name !== 'grid__item' && name !== ' ' )
						cclass += name + ' ';
			}
		});

		if ( cclass !== ' ' ) {
			$('#kcmp-custom-class').val(cclass);
		} else {
			$('#kcmp-custom-class').val('');
		}

		if ( this.state.content[id].class.indexOf('center') > 0 ) {
			$('#kcmp-cc').prop('checked', true);
		} else {
			$('#kcmp-cc').prop('checked', false);
		}

	}

	// Edits an existing column's size

	changeColumnSize(id, increase, e) {

		e.preventDefault();

		let currentItem = Object.assign({}, this.state.content[id]);
		let currentSize = currentItem['size'];

		if ( increase ) {

			if ( currentSize + 1 <= 12 ) {
				currentItem['size'] = currentSize + 1;
			}

		} else {

			if ( currentSize - 1 >= 1 ) {
				currentItem['size'] = currentSize - 1;
			}

		}

		currentItem['class'] = this._setColumnWidth(currentSize, currentItem['size'], currentItem['class']);

		let newState = this.state.content.slice();
		newState[id] = currentItem;

		this.setState({ content: newState });

	}

	// Removes an existing column

	removeColumn(id, e) {
		
		e.preventDefault();

		let remove = confirm(KCMl10n.columnConfirm);

		if ( remove ) {
			let newState = this.state.content.slice();
			newState.splice(id, 1);
			this.setState({ content: newState });
		}

	}

	// Clones an existing column

	cloneColumn(id, e) {

		e.preventDefault();

		let newState = this.state.content.slice();
		let column = Object.assign({}, newState[id]);

		newState.push(column);
		this.setState({ content: newState });	

	}

	/* ------ POPUP ACTIONS -----
		----
			-- */

	// Edits the content of a column in the state

	editColumnContent() {

		let newState = this.state.content.slice();

		let innerValue = $('#wp-kcm_mce-wrap').hasClass('html-active') ? $('#kcm_mce.wp-editor-area').val() : window.tinymce.get('kcm_mce').getContent();
		let classValue = `grid__item ${$('#kcmp-width select').val()} ${$('#kcmp-width-po select').val()} ${$('#kcmp-width-la select').val()} ${$('#kcmp-width-pa select').val()} ${$('#kcmp-padding-top select').val()} ${$('#kcmp-padding-bottom select').val()} ${($('#kcmp-cc').is(':checked') ? 'center' : '')} ${$('#kcmp-custom-class').val()}`;
		let sizeValue = $('#kcmp-width select option:selected').index()+1;

		if ( $('#kcmp').data('id') === 'none' ) {

			// insert new column
			newState.push({
				inner: innerValue,
				class: classValue,
				size: sizeValue
			});

		} else {

			// edit existing column
			newState[$('#kcmp').data('id')] = {
				inner: innerValue,
				class: classValue,
				size: sizeValue
			}

		}

		this.setState({content: newState});

		// close popup

		$('#kcmp').removeClass('open');
		$('body').removeClass('kill-overflow');

		$('#wpadminbar').css('zIndex', '99999');

	}

	/* --------- HELPERS -----
		----
			-- */

	// Gets the column width in number (based on class text)

	_getColumnWidth($column) {
		if ( $column.hasClass('one-twelfth') ) {
			return 1;
		} else if ( $column.hasClass('two-twelfths') ) {
			return 2;
		} else if ( $column.hasClass('three-twelfths') ) {
			return 3;
		} else if ( $column.hasClass('four-twelfths') ) {
			return 4;
		} else if ( $column.hasClass('five-twelfths') ) {
			return 5;
		} else if ( $column.hasClass('six-twelfths') ) {
			return 6;
		} else if ( $column.hasClass('seven-twelfths') ) {
			return 7;
		} else if ( $column.hasClass('eight-twelfths') ) {
			return 8;
		} else if ( $column.hasClass('nine-twelfths') ) {
			return 9;
		} else if ( $column.hasClass('ten-twelfths') ) {
			return 10;
		} else if ( $column.hasClass('eleven-twelfths') ) {
			return 11;
		} else if ( $column.hasClass('one-whole') ) {
			return 12;
		}
	}

	// Returns a new column width (class text) after an increase/decrease

	_setColumnWidth(oldSize, newSize, className) {

		let newSizeName = ''

		switch (newSize) {

			case 1:
				newSizeName = 'one-twelfth';
				break;
			case 2:
				newSizeName = 'two-twelfths';
				break;
			case 3:
				newSizeName = 'three-twelfths';
				break;
			case 4:
				newSizeName = 'four-twelfths';
				break;
			case 5:
				newSizeName = 'five-twelfths';
				break;
			case 6:
				newSizeName = 'six-twelfths';
				break;
			case 7:
				newSizeName = 'seven-twelfths';
				break;
			case 8:
				newSizeName = 'eight-twelfths';
				break;
			case 9:
				newSizeName = 'nine-twelfths';
				break;
			case 10:
				newSizeName = 'ten-twelfths';
				break;
			case 11:
				newSizeName = 'eleven-twelfths';
				break;
			case 12:
				newSizeName = 'one-whole';
				break;

		}

		let newClassName = className.replace(/one-twelfth|two-twelfths|three-twelfths|four-twelfths|five-twelfths|six-twelfths|seven-twelfths|eight-twelfths|nine-twelfths|ten-twelfths|eleven-twelfths|one-whole/, newSizeName);

		return newClassName;

	}

	/* ------ render() -----
		----
			-- */

	render() {
		return (
			<div id="kcm-grid" className="grid">
				{this.state.content.map((column, id) => {
					return(
						<div key={id} data-id={id} data-size={column.size} className={column.class}>
							<div>
								<div dangerouslySetInnerHTML={{__html: column.inner}} />
							</div>
							<div className="controller hide-before-hover">
								<button onClick={this.editExistingColumn.bind(null, id)} className="edit">Edit</button>
								<button onClick={this.cloneColumn.bind(null, id)} className="clone">Clone</button>
								<button onClick={this.changeColumnSize.bind(null, id, true)} className="increase">Increase width</button>
								<button onClick={this.changeColumnSize.bind(null, id, false)} className="decrease">Decrease width</button>
								<button onClick={this.removeColumn.bind(null, id)} className="remove">Remove</button>
							</div>
							<div className="mover hide-before-hover">
								<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="18 18 26 26" version="1.1"><g stroke="none" strokeWidth="1" fill="none" fillRule="evenodd" transform="translate(18.012193, 18.987807)" strokeLinecap="round" strokeLinejoin="round"><path d="M8.1 16.1L1.2 23.1M23.1 1.1L16.2 8.1M16.2 16.1L23.1 23.1M1.2 1.1L8.1 8.1" stroke="#000000"/><polyline stroke="#000000" points="19.4 0.6 23.9 0.6 23.9 5.1"/><polyline stroke="#000000" points="19.4 23.6 23.9 23.6 23.9 19.1"/><polyline stroke="#000000" points="5.4 0.6 0.9 0.6 0.9 5.1"/><polyline stroke="#000000" points="5.4 23.6 0.9 23.6 0.9 19.1"/></g></svg>
							</div>
						</div>
					)}
		    )}
			</div>
		)
	}

}

ReactDOM.render(
	<App />,
	document.getElementById('krown-column-manager-app')
)