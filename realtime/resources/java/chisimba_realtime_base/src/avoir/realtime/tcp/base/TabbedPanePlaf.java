/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.tcp.base;

import avoir.realtime.tcp.common.Constants;
import avoir.realtime.tcp.common.Flash;
import avoir.realtime.tcp.common.ImageUtil;
import avoir.realtime.tcp.common.WebPage;
import avoir.realtime.tcp.common.packet.RemoveDocumentPacket;
import java.awt.Color;
import java.awt.Container;
import java.awt.Insets;
import java.awt.LayoutManager;
import java.awt.Rectangle;
import java.awt.event.ActionEvent;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import javax.swing.AbstractAction;
import javax.swing.JButton;
import javax.swing.plaf.basic.BasicTabbedPaneUI;
import javax.swing.plaf.basic.BasicTabbedPaneUI.TabbedPaneLayout;

public class TabbedPanePlaf extends BasicTabbedPaneUI {

    private RealtimeBase base;
    private String icon;
    private String id;
    private String tooltip;

    public RealtimeBase getBase() {
        return base;
    }

    public void setBase(RealtimeBase base, String icon, String id, String tooltip) {
        this.base = base;
        this.icon = icon;
        this.id = id;
        this.tooltip = tooltip;
    }

    //override to return our layoutmanager
    protected LayoutManager createLayoutManager() {

        return new TabPlafLayout();

    }
    //add 40 to the tab size to allow room for the close button and 8 to the height
    protected Insets getTabInsets(int tabPlacement, int tabIndex) {

        //note that the insets that are returned to us are not copies.

        Insets defaultInsets = (Insets) super.getTabInsets(tabPlacement, tabIndex).clone();

        defaultInsets.right += 40;

        //defaultInsets.top += 4;

        // defaultInsets.bottom += 4;

        return defaultInsets;

    }

    class TabPlafLayout extends TabbedPaneLayout {
        //a list of our close buttons
        java.util.ArrayList closeButtons = new java.util.ArrayList();

        public void layoutContainer(Container parent) {

            super.layoutContainer(parent);

            //ensure that there are at least as many close buttons as tabs

            while (tabPane.getTabCount() > closeButtons.size()) {

                closeButtons.add(new CloseButton(closeButtons.size()));

            }

            Rectangle rect = new Rectangle();

            int i;

            for (i = 0; i < tabPane.getTabCount(); i++) {

                rect = getTabBounds(i, rect);

                JButton closeButton = (JButton) closeButtons.get(i);

                //shift the close button 3 down from the top of the pane and 20 to the left

                closeButton.setLocation(rect.x + rect.width - 25, rect.y + 5);

                closeButton.setSize(20, 15);

                tabPane.add(closeButton);

            }



            for (; i < closeButtons.size(); i++) {

                //remove any extra close buttons

                tabPane.remove((JButton) closeButtons.get(i));

            }

        }
        // implement UIResource so that when we add this button to the
        // tabbedpane, it doesn't try to make a tab for it!
        class CloseButton extends JButton implements javax.swing.plaf.UIResource {

            public CloseButton(int index) {

                super(new CloseButtonAction(index));
                setToolTipText(tooltip);
                this.setIcon(ImageUtil.createImageIcon(this, icon));
                this.setBorderPainted(false);
                this.setContentAreaFilled(false);
               // this.setEnabled(base.getControl());
                //remove the typical padding for the button

                setMargin(new Insets(0, 0, 0, 0));

                addMouseListener(new MouseAdapter() {

                    public void mouseEntered(MouseEvent e) {

                        setForeground(new Color(255, 0, 0));
                        setContentAreaFilled(true);

                    }

                    public void mouseExited(MouseEvent e) {

                        setForeground(new Color(0, 0, 0));
                        setContentAreaFilled(false);

                    }
                });

            }
        }

        class CloseButtonAction extends AbstractAction {

            int index;

            public CloseButtonAction(int index) {

                super("");


                this.index = index;

            }

            private Flash getFlash(String filename) {
                for (int i = 0; i < base.getFlashFiles().size(); i++) {
                    if (base.getFlashFiles().elementAt(i).getFilename().equals(filename)) {
                        return base.getFlashFiles().elementAt(i);
                    }
                }
                return null;
            }

            private WebPage getWebPage(String filename) {
                for (int i = 0; i < base.getWebPages().size(); i++) {
                    System.out.println(base.getWebPages().elementAt(i).getUrl() + " against " + filename);

                    if (base.getWebPages().elementAt(i).getUrl().equals(filename)) {
                        return base.getWebPages().elementAt(i);
                    }
                }
                return null;
            }

            public void actionPerformed(ActionEvent e) {
                if (index == 0 && id.equals("Chat")) {
                    base.showChatRoom();
                }
                if (index > 0 && id.equals("Surface") && base.getControl()) {
                    String title = tabPane.getTitleAt(index);
                    String id = "";
                    String sessionId = base.getSessionId();
                    if (title.startsWith("Flash")) {
                        Flash flash = getFlash(title.substring(6).trim());
                        id = flash.getId();
                        base.getTcpClient().sendPacket(new RemoveDocumentPacket(sessionId, id, Constants.FLASH));

                    } else {
                        title = tabPane.getToolTipTextAt(index);
                        WebPage webPage = getWebPage(title);
                        id = webPage.getId();
                        base.getTcpClient().sendPacket(new RemoveDocumentPacket(sessionId, id, Constants.WEBPAGE));

                    }
                    tabPane.remove(index);
                }
            }
        }
    }
}	
	
