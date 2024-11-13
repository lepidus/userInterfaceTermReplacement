describe('Plugin configuration', function () {
	it('Checks plugin is installed, but does not enable it yet', function () {
		cy.login('dbarnes', null, 'publicknowledge');
		cy.contains('a', 'Website').click();

		cy.waitJQuery();
		cy.get('#plugins-button').click();

		cy.get('input[id^=select-cell-userinterfacetermreplacementplugin]').uncheck();
		cy.get('input[id^=select-cell-userinterfacetermreplacementplugin]').should('not.be.checked');
	});
});
