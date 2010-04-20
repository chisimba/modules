/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.wits.client.ads;

import com.extjs.gxt.ui.client.Style.HorizontalAlignment;
import com.extjs.gxt.ui.client.event.ButtonEvent;
import com.extjs.gxt.ui.client.event.SelectionListener;
import com.extjs.gxt.ui.client.widget.Dialog;
import com.extjs.gxt.ui.client.widget.MessageBox;
import com.extjs.gxt.ui.client.widget.button.Button;
import com.extjs.gxt.ui.client.widget.form.TextField;
import com.google.gwt.core.client.GWT;
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
    private RulesAndSyllabusTwo rulesAndSyllabusTwo;
    private TextField<String> forwardTo = new TextField<String>();
    private Button forwardButton = new Button();
    //private FormData formData = new FormData();
    private String email;

    public ForwardTo() {
        creatUI();
    }

    public void creatUI() {

        forwardTo.setWidth(300);
        forwardTo.setHeight(40);
        forwardTo.setEmptyText("please enter the email address to forward to...");
        forwardTo.setAllowBlank(false);

        forwardButton.setText("forward...");

        forwardButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                email = forwardTo.getValue();
                
                if (email == null) {
                    MessageBox.info("Error", "please enter a valid email address", null);
                    return;
                }

                String url= GWT.getHostPageBaseURL() + Constants.MAIN_URL_PATTERN
                        + "?module=wicid&action=forwardto&link="+"link"+"&email=" +email+"&docid="+Constants.docid;
                MessageBox.info("Message", url, null);
                sendEmail(url);

                forwardToDialog.close();
            }
        });


        forwardToDialog.add(forwardTo);
        forwardToDialog.setBodyBorder(false);
        forwardToDialog.setHeading("forward To this email");
        forwardToDialog.setHeight(100);
        forwardToDialog.setWidth(320);
        forwardToDialog.setButtons(Dialog.CLOSE);
        forwardToDialog.setButtonAlign(HorizontalAlignment.LEFT);
        forwardToDialog.setHideOnButtonClick(true);
        forwardToDialog.addButton(forwardButton);


    }

    public void show() {
        forwardToDialog.show();
    }

    private void sendEmail(String url) {

        RequestBuilder builder = new RequestBuilder(RequestBuilder.GET, url);

        try {

            Request request = builder.sendRequest(null, new RequestCallback() {

                public void onError(Request request, Throwable exception) {
                    MessageBox.info("Error", "Error, cannot create new document", null);
                }

                public void onResponseReceived(Request request, Response response) {
                    String resp[] = response.getText().split("|");

                    if (resp[0].equals("")) {
                        /*if (oldOverView == null) {

                        Constants.docid = resp[1];
                        OverView overView = new OverView(NewCourseProposalDialog.this);
                        overView.show();
                        newDocumentDialog.hide();
                        } else {
                        oldOverView.show();
                        newDocumentDialog.hide();

                        }*/
                    } else {
                        MessageBox.info("Error", "Error occured on the server. Cannot create document", null);
                    }
                }
            });
        } catch (Exception e) {
            MessageBox.info("Fatal Error", "Fatal Error: cannot create new document", null);
        }

    }
}
