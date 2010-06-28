package org.wits.client.ads;

import java.util.ArrayList;
import java.util.List;
import com.extjs.gxt.ui.client.Style.HorizontalAlignment;
import com.extjs.gxt.ui.client.Style.LayoutRegion;
import com.extjs.gxt.ui.client.Style.Scroll;
import com.extjs.gxt.ui.client.event.ButtonEvent;
import com.extjs.gxt.ui.client.event.SelectionListener;

import com.extjs.gxt.ui.client.store.ListStore;
import com.extjs.gxt.ui.client.util.Margins;
import com.extjs.gxt.ui.client.widget.ContentPanel;
import com.extjs.gxt.ui.client.widget.Dialog;
import com.extjs.gxt.ui.client.widget.Label;
import com.extjs.gxt.ui.client.widget.MessageBox;
import com.extjs.gxt.ui.client.widget.button.Button;
import com.extjs.gxt.ui.client.widget.form.FormPanel;
import com.extjs.gxt.ui.client.widget.form.TextField;
import com.extjs.gxt.ui.client.widget.grid.ColumnConfig;
import com.extjs.gxt.ui.client.widget.grid.ColumnModel;
import com.extjs.gxt.ui.client.widget.grid.Grid;
import com.extjs.gxt.ui.client.widget.layout.BorderLayoutData;
import com.extjs.gxt.ui.client.widget.layout.FitLayout;
import com.extjs.gxt.ui.client.widget.layout.FormData;
//import com.google.gwt.user.client.ui.Grid;
import com.google.gwt.core.client.GWT;
import com.google.gwt.core.client.JsArray;
import com.google.gwt.http.client.Request;
import com.google.gwt.http.client.RequestBuilder;
import com.google.gwt.http.client.RequestCallback;
import com.google.gwt.http.client.Response;
import org.wits.client.Constants;

/**
 *
 * @author luigi
 */
public class ForwardTo {

    private Dialog forwardToDialog = new Dialog();
    private Dialog searchDialog = new Dialog();
    private RulesAndSyllabusTwo rulesAndSyllabusTwo;
    private TextField forwardTo = new TextField();
    private Button forwardButton = new Button();
    private Button searchButton = new Button("Search");
    private FormPanel mainForm = new FormPanel();
    private FormPanel searchForm = new FormPanel();
    private FormData formData = new FormData("-20");
    //private FormData formData = new FormData();
    private String email;
    private BorderLayoutData centerData = new BorderLayoutData(LayoutRegion.CENTER);
    private BorderLayoutData eastData = new BorderLayoutData(LayoutRegion.EAST, 80);
    private ListStore<User> userStore = new ListStore<User>();

    public ForwardTo() {
        creatUI();
    }

    public void creatUI() {
        /* mainForm.setFrame(false);
        mainForm.setBodyBorder(false);
        mainForm.setWidth(480);
         */

        centerData.setMargins(new Margins(0));

        forwardTo.setWidth(300);
        forwardTo.setHeight(25);
        forwardTo.setAllowBlank(false);

        //mainForm.add(forwardTo, centerData);

        searchButton.setSize(80, 25);
        searchButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {

                searchDialog();

            }
        });

        com.google.gwt.user.client.ui.Grid forwardGrid = new com.google.gwt.user.client.ui.Grid(2, 2);
        forwardGrid.setWidget(0, 0, forwardTo);
        forwardGrid.setWidget(0, 1, searchButton);

        FormPanel panel = new FormPanel();
        panel.setSize(400, 140);
        panel.add(forwardGrid);

        forwardButton.setText("Forward");
        forwardButton.setSize(80, 22);
        forwardButton.enableEvents(true);
        forwardButton.setPagePosition(150, 100);
        forwardButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                email = forwardTo.getValue().toString();

                if (forwardTo.getValue() == null) {
                    MessageBox.info("Error", "Please provide a person to forward to.", null);
                    return;
                }

                String url = GWT.getHostPageBaseURL() + Constants.MAIN_URL_PATTERN
                        + "?module=wicid&action=forwardto&link=" + "link" + "&email=" + email + "&docid=" + Constants.docid;
                MessageBox.info("Message", url, null);
                sendEmail(url);

                forwardToDialog.close();
            }
        });
        panel.add(forwardButton);

        forwardToDialog.setBodyBorder(false);
        forwardToDialog.setHeading("Forward to...");
        forwardToDialog.setHeight(180);
        forwardToDialog.setWidth(412);
        forwardToDialog.setButtons(Dialog.CLOSE);
        forwardToDialog.setButtonAlign(HorizontalAlignment.LEFT);
        forwardToDialog.setHideOnButtonClick(true);
        forwardToDialog.add(panel, formData);
    }

    public void show() {
        forwardToDialog.show();
    }

    public void searchDialog() {
        searchDialog.setBodyBorder(false);
        searchDialog.setHeading("Search");
        searchDialog.setWidth(400);
        searchDialog.setHeight(350);
        searchDialog.setButtons(Dialog.CLOSE);
        searchDialog.setButtonAlign(HorizontalAlignment.LEFT);

        searchForm.setHeight(280);


        final TextField inputField = new TextField();
        inputField.setEmptyText("Enter partial email");
        inputField.setSize(250, 25);

        Button searchB = new Button("Search");
        searchB.setSize(80, 25);
        searchB.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                String value = inputField.getValue().toString();
            }
        });

        com.google.gwt.user.client.ui.Grid inputGrid = new com.google.gwt.user.client.ui.Grid(2, 2);
        inputGrid.setWidget(0, 0, inputField);
        inputGrid.setWidget(0, 1, searchB);
        searchForm.add(inputGrid, formData);

        List<ColumnConfig> configs = new ArrayList<ColumnConfig>();

        ColumnConfig column = new ColumnConfig();
        column.setId("firstname");
        column.setHeader("First Name");
        column.setWidth(75);
        configs.add(column);

        column = new ColumnConfig();
        column.setId("surname");
        column.setHeader("Surname");
        column.setWidth(75);
        configs.add(column);

        column = new ColumnConfig();
        column.setId("email");
        column.setHeader("Email Address");
        column.setAlignment(HorizontalAlignment.RIGHT);
        column.setWidth(200);
        configs.add(column);

        //userStore.add(getUsers());

        ColumnModel cm = new ColumnModel(configs);

        ContentPanel cp = new ContentPanel();
        cp.setBodyBorder(false);
        //cp.setIcon(Resources.ICONS.table());
        cp.setHeading("Results");
        cp.setButtonAlign(HorizontalAlignment.CENTER);
        cp.setLayout(new FitLayout());
        cp.setSize(600, 150);
        cp.setScrollMode(Scroll.AUTO);

        Grid<User> emailGrid = new Grid<User>(userStore, cm);
        emailGrid.setStyleAttribute("borderTop", "none");
        emailGrid.setAutoExpandColumn("name");
        emailGrid.setBorders(true);
        emailGrid.setStripeRows(true);
        cp.add(emailGrid);

        searchForm.add(cp, formData);
        searchForm.add(new Label());

        Button doneButton = new Button("Done");
        doneButton.setSize(80, 25);
        doneButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                forwardTo.setRawValue(inputField.getValue().toString());
                searchDialog.hide();
            }
        });
        searchForm.add(doneButton);

        searchForm.setHeading("Enter email address:");

        searchDialog.add(searchForm);
        searchDialog.show();
    }

    private void sendEmail(String url) {

        RequestBuilder builder = new RequestBuilder(RequestBuilder.GET, url);

        try {

            Request request = builder.sendRequest(null, new RequestCallback() {

                public void onError(Request request, Throwable exception) {
                    MessageBox.info("Error", "Error, cannot create new document", null);
                }

                public void onResponseReceived(Request request, Response response) {

                }
            });
        } catch (Exception e) {
            MessageBox.info("Fatal Error", "Fatal Error: cannot create new document", null);
        }

    }

    private List<User> getUsers() {
        List<User> users = new ArrayList<User>();
        users.add(new User("Jane", "Smith", "janesmith@wits.ac.za"));
        users.add(new User("Jacqueline", "Gil", "jacqueline.gil@students.wits.ac.za"));
        return users;
    }

    /**
     * Convert the string of JSON into JavaScript object.
     */
    private final native JsArray<JSonUser> asArrayOfUser(String json) /*-{
    return eval(json);
    }-*/;

    private void search(String val) {
        String url = Constants.MAIN_URL_PATTERN + "/?module=wicid&action=search&value=" + val;
        RequestBuilder builder = new RequestBuilder(RequestBuilder.GET, url);

        try {

            Request request = builder.sendRequest(null, new RequestCallback() {

                public void onError(Request request, Throwable exception) {
                    MessageBox.info("Error", "Error, cannot create new document", null);
                }

                public void onResponseReceived(Request request, Response response) {
                      if (200 == response.getStatusCode()) {
                        JsArray<JSonUser> users = asArrayOfUser(response.getText());
                        List<User> userlist = new ArrayList<User>();

                        for (int i = 0; i < users.length(); i++) {
                            JSonUser jSonUser = users.get(i);
                            User user = new User(jSonUser.getFirstName(), jSonUser.getSurname(), jSonUser.getEmail());
                            userlist.add(user);
                        }
                        userStore.add(userlist);
//                        userStore.
                    }
                }
            });
        } catch (Exception e) {
            MessageBox.info("Fatal Error", "Fatal Error: cannot create new document", null);
        }

    }
}
