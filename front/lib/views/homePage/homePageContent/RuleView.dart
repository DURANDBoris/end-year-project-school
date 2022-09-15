import 'package:flutter/material.dart';
import '../../../models/core/Document.dart';
import '../../../models/core/Rule.dart';
import '/providers/AppBarProvider.dart';
import 'package:provider/provider.dart';
import 'package:tuple/tuple.dart';

import '../../../providers/HomePageProvider.dart';

class RuleWidget extends StatefulWidget {
  const RuleWidget({Key? key}) : super(key: key);

  @override
  State<RuleWidget> createState() => _RuleWidgetState();
}

class _RuleWidgetState extends State<RuleWidget> {
  late HomePageProvider homePageProvider;
  late AppBarProvider appBarProvider;
  ScrollController scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    homePageProvider = Provider.of<HomePageProvider>(context, listen: false);
    appBarProvider = Provider.of<AppBarProvider>(context, listen: false);
  }

  List<Widget> listRule(List<Rule> listRule) {
    List<Widget> listWidget = [
      SizedBox(
        height: 30,
      )
    ];

    for (Rule rule in listRule) {
      listWidget.add(
        Container(
          height: 50,
          padding: EdgeInsets.only(left: 10, right: 20),
          decoration: BoxDecoration(
            color: Colors.grey[200],
            borderRadius: BorderRadius.all(
              Radius.circular(5),
            ),
          ),
          child: Row(
            mainAxisSize: MainAxisSize.min,
            children: [
              Text("${rule.name} : "),
              SizedBox(
                width: 8,
              ),
              Expanded(
                  child: TextField(
                decoration: InputDecoration(border: InputBorder.none),
              )),
            ],
          ),
        ),
      );
      listWidget.add(SizedBox(
        height: 15,
      ));
    }

    return listWidget;
  }

  @override
  Widget build(BuildContext context) {
    return Selector2<AppBarProvider, HomePageProvider,
        Tuple2<bool, List<Rule>>>(
      selector: (_, provider1, provider2) =>
          Tuple2(provider1.isRuleOpen, provider2.listRule),
      builder: (_, data, __) {
        if (data.item1) {
          return Expanded(
            flex: 25,
            child: Column(
              children: [
                Expanded(
                  child: Scrollbar(
                    controller: scrollController,
                    thumbVisibility: true,
                    child: SingleChildScrollView(
                      controller: scrollController,
                      child: Column(
                        mainAxisSize: MainAxisSize.min,
                        children: <Widget>[
                              SizedBox(
                                height: 30,
                              ),
                              Text(
                                "Detailled search for folder :",
                                style: TextStyle(
                                  shadows: [
                                    Shadow(
                                      color: Colors.black,
                                      offset: Offset(0, -5),
                                    )
                                  ],
                                  color: Colors.transparent,
                                  decorationColor: Colors.black,
                                  decoration: TextDecoration.underline,
                                ),
                              ),
                            ] +
                            listRule(
                              homePageProvider.listRule,
                            ),
                      ),
                    ),
                  ),
                ),
              ],
            ),
          );
        } else {
          return Container();
        }
      },
    );
  }
}
