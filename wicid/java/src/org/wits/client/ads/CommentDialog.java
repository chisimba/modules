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
import com.extjs.gxt.ui.client.widget.form.FormPanel;
import com.extjs.gxt.ui.client.widget.layout.FormData;
import com.google.gwt.core.client.GWT;
import com.google.gwt.http.client.Request;
import com.google.gwt.http.client.RequestBuilder;
import com.google.gwt.http.client.RequestCallback;
import com.google.gwt.http.client.Response;
import org.wits.client.Constants;

/**
 *
 * @author Jacqueline Gil
 */
public class CommentDialog {

    private Dialog commentDialog = new Dialog();
    private Button commentButton = new Button("Done");
    private FormPanel panel = new FormPanel();
    private FormData formData = new FormData("-20");
    private String currentUserId;

    public CommentDialog() {
        createUI();
    }

    public void createUI() {
        panel.setWidth(300);
        panel.setHeight(25);

        commentButton.setSize(80, 25);
        commentButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                RequestBuilder builder = new RequestBuilder(RequestBuilder.GET, GWT.getHostPageBaseURL() +
                        Constants.MAIN_URL_PATTERN + "?module=wicid&action=setCommentData&docid=" +
                        Constants.docid+"&currentuserid="+currentUserId);
                try {

                    Request request = builder.sendRequest(null, new RequestCallback() {

                        public void onError(Request request, Throwable exception) {
                            MessageBox.info("Error", "Error, cannot change currentuser", null);
                        }

                        public void onResponseReceived(Request request, Response response) {
                            MessageBox.info("Done", "The current user for document "+Constants.docid+" has been changed.", null);
                        }
                    });
                } catch (Exception e) {
                    MessageBox.info("Fatal Error", "Fatal Error: cannot change currentuser", null);
                }

                commentDialog.hide();
            }
        });
        panel.add(commentButton);

        commentDialog.setBodyBorder(false);
        commentDialog.setHeading("Forward to...");
        commentDialog.setHeight(180);
        commentDialog.setWidth(412);
        commentDialog.setButtons(Dialog.CLOSE);
        commentDialog.setButtonAlign(HorizontalAlignment.LEFT);
        commentDialog.setHideOnButtonClick(true);
        commentDialog.add(panel, formData);
    }

    public void show() {
        commentDialog.show();
    }
}
